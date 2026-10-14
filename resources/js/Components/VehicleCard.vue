<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    vehicle: {
        type: Object,
        required: true,
    },
    period: {
        type: String,
        default: 'daily',
    },
});

const emit = defineEmits(['open-detail', 'select-booking']);

// Interactive pricing tab: 'daily' | 'weekly' | 'monthly'
const priceMode = ref(props.period || 'daily');

watch(
    () => props.period,
    (newVal) => {
        if (newVal) {
            priceMode.value = newVal;
        }
    }
);

const currentPrice = computed(() => {
    if (!props.vehicle.prices) return 0;
    return props.vehicle.prices[priceMode.value] || props.vehicle.prices.daily || 0;
});

const durationSuffix = computed(() => {
    switch (priceMode.value) {
        case 'weekly':
            return '/ minggu';
        case 'monthly':
            return '/ bulan';
        default:
            return '/ hari';
    }
});

const discountBadge = computed(() => {
    if (priceMode.value === 'weekly') return 'Hemat ~10%';
    if (priceMode.value === 'monthly') return 'Hemat ~25%';
    return null;
});

const formatRupiah = (val) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(val);
};

const getWhatsAppUrl = () => {
    const periodLabel = priceMode.value === 'weekly' ? 'Mingguan' : priceMode.value === 'monthly' ? 'Bulanan' : 'Harian';
    const statusNote = props.vehicle.is_available ? '' : '\n(Catatan: Saya ingin cek ketersediaan untuk tanggal sewa berikutnya)';
    const text = encodeURIComponent(
        `Halo Admin X-RENT, saya tertarik untuk menyewa unit:\n*${props.vehicle.name}*\nPaket: *${periodLabel}* (${formatRupiah(currentPrice.value)}${durationSuffix.value})\nOpsi: *${props.vehicle.driver_option || 'Lepas Kunci'}*${statusNote}\nBisa minta info ketersediaan dan syarat sewanya?`
    );
    return `https://wa.me/6281288889999?text=${text}`;
};
</script>

<template>
    <div
        class="group flex flex-col overflow-hidden rounded-2xl bg-surface-container-lowest shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl border border-surface-container-highest/60"
    >
        <!-- Vehicle Image & Badges -->
        <div class="relative h-48 sm:h-52 w-full overflow-hidden bg-surface-container-low">
            <img
                :src="vehicle.image"
                :alt="vehicle.name"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                loading="lazy"
            />

            <!-- Left Category/Feature Badge -->
            <span
                v-if="vehicle.badge"
                class="absolute left-3 top-3 rounded-full bg-primary px-3 py-1 font-label-badge text-label-badge text-on-primary shadow-sm"
            >
                {{ vehicle.badge }}
            </span>

            <!-- Right Availability Status Badge (PRD Requirement) -->
            <div
                class="absolute right-3 top-3 flex items-center gap-1.5 rounded-full px-2.5 py-1 backdrop-blur-md shadow-xs text-xs font-semibold"
                :class="[
                    vehicle.is_available
                        ? 'bg-surface-container-lowest/95 text-tertiary border border-tertiary/20'
                        : 'bg-surface-container-highest/95 text-on-surface-variant border border-outline-variant'
                ]"
            >
                <span
                    class="h-2 w-2 rounded-full"
                    :class="[
                        vehicle.is_available
                            ? 'bg-tertiary animate-pulse'
                            : 'bg-amber-500'
                    ]"
                ></span>
                <span class="font-spec-data text-[11px]">
                    {{ vehicle.status_label || (vehicle.is_available ? 'Tersedia Hari Ini' : 'Sedang Disewa') }}
                </span>
            </div>

            <!-- Driver Option Overlay Pill -->
            <div class="absolute bottom-3 left-3">
                <span class="rounded-lg bg-surface-container-lowest/90 px-2.5 py-1 font-spec-data text-[11px] font-semibold text-on-surface backdrop-blur-md shadow-xs">
                    {{ vehicle.driver_option || 'Lepas Kunci' }}
                </span>
            </div>
        </div>

        <!-- Card Body -->
        <div class="flex flex-1 flex-col p-5">
            <!-- Header Meta -->
            <div class="flex items-baseline justify-between">
                <span class="font-spec-data text-spec-data uppercase tracking-wider text-on-surface-variant">
                    {{ vehicle.category_label || vehicle.category }}
                </span>
                <span class="font-spec-data text-spec-data font-semibold text-primary">
                    Tahun {{ vehicle.year }}
                </span>
            </div>

            <h3 class="mt-1 font-headline-sm text-headline-sm font-bold text-on-surface group-hover:text-primary transition">
                {{ vehicle.name }}
            </h3>

            <!-- Telemetry Spec Matrix Grid -->
            <div class="mt-4 grid grid-cols-2 gap-2">
                <div
                    v-for="(spec, idx) in vehicle.specs"
                    :key="idx"
                    class="flex items-center gap-2 rounded-lg bg-surface-container-low px-2.5 py-2 text-on-surface"
                >
                    <span class="material-symbols-outlined text-[18px] text-primary shrink-0">
                        {{ spec.icon }}
                    </span>
                    <span class="font-spec-data text-[12px] truncate">{{ spec.label }}</span>
                </div>
            </div>

            <!-- Pricing Section & Interactive Tabs (PRD Requirement) -->
            <div class="mt-5 pt-4 border-t border-surface-container-highest/60 flex flex-col gap-3">
                <!-- Interactive Period Tabs -->
                <div class="flex items-center justify-between">
                    <span class="font-label-badge text-[11px] uppercase text-on-surface-variant font-bold">
                        Pilih Periode Sewa:
                    </span>
                    <span
                        v-if="discountBadge"
                        class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-tertiary-fixed text-tertiary uppercase tracking-wider"
                    >
                        {{ discountBadge }}
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-1 rounded-xl bg-surface-container p-1 font-spec-data text-xs font-semibold">
                    <button
                        type="button"
                        @click="priceMode = 'daily'"
                        class="rounded-lg py-1.5 text-center transition-all"
                        :class="[
                            priceMode === 'daily'
                                ? 'bg-surface-container-lowest text-primary font-bold shadow-xs'
                                : 'text-on-surface-variant hover:text-on-surface'
                        ]"
                    >
                        Harian
                    </button>
                    <button
                        type="button"
                        @click="priceMode = 'weekly'"
                        class="rounded-lg py-1.5 text-center transition-all"
                        :class="[
                            priceMode === 'weekly'
                                ? 'bg-surface-container-lowest text-primary font-bold shadow-xs'
                                : 'text-on-surface-variant hover:text-on-surface'
                        ]"
                    >
                        Mingguan
                    </button>
                    <button
                        type="button"
                        @click="priceMode = 'monthly'"
                        class="rounded-lg py-1.5 text-center transition-all"
                        :class="[
                            priceMode === 'monthly'
                                ? 'bg-surface-container-lowest text-primary font-bold shadow-xs'
                                : 'text-on-surface-variant hover:text-on-surface'
                        ]"
                    >
                        Bulanan
                    </button>
                </div>

                <!-- Price Display -->
                <div class="flex items-baseline justify-between mt-1">
                    <div>
                        <span class="font-label-badge text-label-badge text-on-surface-variant uppercase">Tarif Sewa</span>
                        <div class="flex items-baseline gap-1.5">
                            <span class="font-headline-sm text-headline-sm font-bold text-primary">
                                {{ formatRupiah(currentPrice) }}
                            </span>
                            <span class="font-spec-data text-spec-data text-on-surface-variant font-normal">
                                {{ durationSuffix }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Grid -->
            <div class="mt-4 grid grid-cols-2 gap-2">
                <button
                    type="button"
                    @click="emit('open-detail', vehicle, currentPrice, priceMode)"
                    class="rounded-lg bg-surface-container-high py-2.5 text-center font-title-md text-body-sm font-semibold text-primary transition hover:bg-surface-variant active:scale-95"
                >
                    Detail Unit
                </button>
                <a
                    :href="getWhatsAppUrl()"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center justify-center gap-1.5 rounded-lg py-2.5 text-center font-title-md text-body-sm text-on-primary shadow-xs transition active:scale-95"
                    :class="[
                        vehicle.is_available
                            ? 'bg-primary hover:bg-primary-container'
                            : 'bg-secondary hover:bg-on-surface-variant'
                    ]"
                >
                    <span class="material-symbols-outlined text-[16px]">
                        {{ vehicle.is_available ? 'shopping_bag' : 'event' }}
                    </span>
                    <span>{{ vehicle.is_available ? 'Sewa Sekarang' : 'Cek Jadwal' }}</span>
                </a>
            </div>
        </div>
    </div>
</template>
