<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import VehicleCard from '@/Components/VehicleCard.vue';

const props = defineProps({
    vehicle: {
        type: Object,
        required: true,
    },
    relatedVehicles: {
        type: Array,
        default: () => [],
    },
});

// Interactive Gallery Active Image
const activeImageIndex = ref(0);
const galleryImages = computed(() => {
    if (props.vehicle.gallery && props.vehicle.gallery.length > 0) {
        return props.vehicle.gallery;
    }
    return [props.vehicle.image];
});

const currentDisplayImage = computed(() => {
    return galleryImages.value[activeImageIndex.value] || props.vehicle.image;
});

// Reactive Calculator States (Vue 3 ref)
const durationPeriod = ref('daily'); // 'daily' | 'weekly' | 'monthly'
const durationCount = ref(1); // Stepper count (1, 2, 3...)
const pickupMethod = ref('garage'); // 'garage' (Rp 0) | 'delivery' (+Rp 25.000)

// Online Booking Form States (Vue 3 ref)
const formFullName = ref('');
const formWhatsapp = ref('');
const formCategory = ref('wisatawan'); // 'mahasiswa' | 'wisatawan' | 'pekerja' | 'umum'
const formStartDate = ref(new Date(Date.now() + 86400000).toISOString().split('T')[0]);
const formStartTime = ref('09:00 WIB');
const formNotes = ref('');
const showBookingSuccessModal = ref(false);

// Reactive Price Computations (Vue 3 computed)
const unitPrice = computed(() => {
    if (!props.vehicle.prices) return props.vehicle.price_daily || 0;
    return props.vehicle.prices[durationPeriod.value] || props.vehicle.prices.daily || props.vehicle.price_daily || 0;
});

const pickupFee = computed(() => {
    return pickupMethod.value === 'delivery' ? 25000 : 0;
});

const subtotalDurationPrice = computed(() => {
    return unitPrice.value * Math.max(1, durationCount.value);
});

const totalCalculatedPrice = computed(() => {
    return subtotalDurationPrice.value + pickupFee.value;
});

const durationPeriodLabel = computed(() => {
    switch (durationPeriod.value) {
        case 'weekly':
            return 'Minggu';
        case 'monthly':
            return 'Bulan';
        default:
            return 'Hari';
    }
});

const formatRupiah = (val) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(val || 0);
};

// Stepper Handlers
const incrementDuration = () => {
    durationCount.value++;
};

const decrementDuration = () => {
    if (durationCount.value > 1) {
        durationCount.value--;
    }
};

// WhatsApp Direct URL with Pre-filled Calculator & Form Breakdown
const getWhatsAppBookingUrl = () => {
    const custCategoryLabel = {
        mahasiswa: 'Mahasiswa / Pelajar',
        wisatawan: 'Wisatawan / Liburan',
        pekerja: 'Pekerja / Dinas Kantor',
        umum: 'Pelanggan Umum',
    }[formCategory.value] || 'Umum';

    const pickupLabel = pickupMethod.value === 'delivery'
        ? 'Layanan Antar-Jemput ke Lokasi (+Rp 25.000)'
        : 'Ambil Sendiri di Garasi Pusat (Gratis)';

    let message = `*FORM PEMESANAN KENDARAAN X-RENT*\n`;
    message += `----------------------------------------\n`;
    message += `*Unit:* ${props.vehicle.name}\n`;
    message += `*Kategori:* ${props.vehicle.category_label || props.vehicle.type}\n`;
    message += `*Transmisi:* ${props.vehicle.transmission_label || 'Matic'}\n`;
    message += `*Status:* ${props.vehicle.status_label || (props.vehicle.is_available ? 'Tersedia' : 'Sedang Disewa')}\n\n`;

    message += `*RINCIAN SEWA & KALKULATOR:*\n`;
    message += `- Durasi: ${durationCount.value} ${durationPeriodLabel.value} (${formatRupiah(unitPrice.value)} / ${durationPeriodLabel.value})\n`;
    message += `- Subtotal Sewa: ${formatRupiah(subtotalDurationPrice.value)}\n`;
    message += `- Pengambilan: ${pickupLabel}\n`;
    message += `- Asuransi All-Risk: Termasuk (Gratis)\n`;
    message += `*TOTAL BIAYA ESTIMASI: ${formatRupiah(totalCalculatedPrice.value)}*\n\n`;

    message += `*DATA PEMESAN:*\n`;
    message += `- Nama Lengkap: ${formFullName.value || '-'}\n`;
    message += `- No. WhatsApp: ${formWhatsapp.value || '-'}\n`;
    message += `- Kategori Pelanggan: ${custCategoryLabel}\n`;
    message += `- Tanggal Mulai: ${formStartDate.value} (${formStartTime.value})\n`;
    if (formNotes.value) {
        message += `- Catatan / Alamat Antar: ${formNotes.value}\n`;
    }
    message += `----------------------------------------\n`;
    message += `Mohon konfirmasi ketersediaan unit dan proses verifikasi berkas saya. Terima kasih.`;

    return `https://wa.me/6281288889999?text=${encodeURIComponent(message)}`;
};

const handleOnlineBookingSubmit = (e) => {
    e.preventDefault();
    if (!formFullName.value.trim() || !formWhatsapp.value.trim()) {
        alert('Mohon lengkapi Nama Lengkap dan Nomor WhatsApp Anda.');
        return;
    }
    showBookingSuccessModal.value = true;
};
</script>

<template>
    <Head :title="`${vehicle.name} - Rental & Tarif Sewa | X-RENT`" />

    <MainLayout>
        <div class="flex flex-col w-full bg-surface">
            <!-- BREADCRUMBS & TOP BAR -->
            <section class="w-full bg-surface-container-low border-b border-surface-container-highest/60 py-4">
                <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                    <nav class="flex items-center gap-2 text-xs font-spec-data text-on-surface-variant" aria-label="Breadcrumb">
                        <Link href="/" class="hover:text-primary transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">home</span>
                            <span>Beranda</span>
                        </Link>
                        <span class="text-outline-variant">/</span>
                        <Link href="/katalog" class="hover:text-primary transition-colors">
                            Katalog Armada
                        </Link>
                        <span class="text-outline-variant">/</span>
                        <span class="text-primary font-bold truncate max-w-xs">{{ vehicle.name }}</span>
                    </nav>
                </div>
            </section>

            <!-- MAIN DETAIL & CALCULATOR CONTENT -->
            <section class="w-full py-8 lg:py-14">
                <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                        
                        <!-- LEFT COLUMN: Vehicle Details, Multi-angle Gallery, Specs, and Requirements -->
                        <div class="lg:col-span-7 flex flex-col gap-8">
                            
                            <!-- Vehicle Gallery (Stitch Reference) -->
                            <div class="rounded-3xl bg-surface-container-lowest p-4 sm:p-6 border border-surface-container-highest/70 shadow-sm flex flex-col gap-4">
                                <!-- Main Big Photo -->
                                <div class="relative h-64 sm:h-80 md:h-96 w-full rounded-2xl overflow-hidden bg-surface-container-low">
                                    <img
                                        :src="currentDisplayImage"
                                        :alt="vehicle.name"
                                        class="h-full w-full object-cover transition-all duration-300"
                                    />

                                    <!-- Top Left Category & Brand Badge -->
                                    <div class="absolute left-4 top-4 flex flex-wrap items-center gap-2">
                                        <span class="rounded-full bg-primary px-3.5 py-1 font-label-badge text-xs text-on-primary font-bold shadow-sm">
                                            {{ vehicle.category_label || vehicle.category }}
                                        </span>
                                        <span
                                            v-if="vehicle.badge"
                                            class="rounded-full bg-surface-container-lowest/90 px-3 py-1 font-spec-data text-xs font-semibold text-on-surface backdrop-blur-md shadow-xs"
                                        >
                                            {{ vehicle.badge }}
                                        </span>
                                    </div>

                                    <!-- Top Right Availability Status Badge (PRD Requirement) -->
                                    <div
                                        class="absolute right-4 top-4 flex items-center gap-2 rounded-full px-3.5 py-1.5 backdrop-blur-md shadow-md text-xs font-semibold"
                                        :class="[
                                            vehicle.status === 'available' || (vehicle.status === undefined && vehicle.is_available)
                                                ? 'bg-surface-container-lowest/95 text-tertiary border border-tertiary/20'
                                                : vehicle.status === 'maintenance'
                                                ? 'bg-surface-container-lowest/95 text-rose-700 border border-rose-200'
                                                : 'bg-surface-container-highest/95 text-on-surface-variant border border-outline-variant'
                                        ]"
                                    >
                                        <span
                                            class="h-2.5 w-2.5 rounded-full"
                                            :class="[
                                                vehicle.status === 'available' || (vehicle.status === undefined && vehicle.is_available)
                                                    ? 'bg-tertiary animate-pulse'
                                                    : vehicle.status === 'maintenance'
                                                    ? 'bg-rose-500'
                                                    : 'bg-amber-500'
                                            ]"
                                        ></span>
                                        <span class="font-spec-data text-xs font-bold">
                                            {{ vehicle.status_label || (vehicle.is_available ? 'Tersedia' : 'Sedang Disewa') }}
                                        </span>
                                    </div>

                                    <!-- Bottom Driver Option Pill -->
                                    <div class="absolute bottom-4 left-4">
                                        <span class="rounded-xl bg-surface-container-lowest/95 px-3 py-1.5 font-spec-data text-xs font-bold text-primary backdrop-blur-md shadow-xs">
                                            {{ vehicle.driver_option || 'Lepas Kunci' }} • {{ vehicle.transmission_label || 'Matic' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- 4-Angle Thumbnail Selector Grid -->
                                <div class="grid grid-cols-4 gap-3">
                                    <button
                                        v-for="(img, idx) in galleryImages"
                                        :key="idx"
                                        type="button"
                                        @click="activeImageIndex = idx"
                                        class="relative h-20 sm:h-24 rounded-xl overflow-hidden border-2 transition-all cursor-pointer focus:outline-none"
                                        :class="[
                                            activeImageIndex === idx
                                                ? 'border-primary ring-2 ring-primary/20 scale-[1.02]'
                                                : 'border-surface-container-highest hover:border-outline-variant opacity-75 hover:opacity-100'
                                        ]"
                                    >
                                        <img :src="img" :alt="`${vehicle.name} Sudut ${idx + 1}`" class="w-full h-full object-cover" />
                                        <span class="absolute bottom-1 right-1 rounded bg-black/60 text-[9px] text-white px-1 font-spec-data">
                                            Sudut {{ idx + 1 }}
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <!-- Vehicle Title & Headline Overview -->
                            <div class="rounded-3xl bg-surface-container-lowest p-6 border border-surface-container-highest/70 shadow-sm space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div>
                                        <span class="font-spec-data text-xs uppercase tracking-wider text-primary font-bold">
                                            {{ vehicle.brand }} • Tahun {{ vehicle.year }}
                                        </span>
                                        <h1 class="font-display-hero text-2xl sm:text-3xl font-extrabold text-on-surface mt-1">
                                            {{ vehicle.name }}
                                        </h1>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="rounded-full bg-surface-container-high px-3 py-1 text-xs font-semibold text-on-surface">
                                            Transmisi: <strong>{{ vehicle.transmission_label || 'Matic' }}</strong>
                                        </span>
                                    </div>
                                </div>

                                <p v-if="vehicle.subtitle" class="font-body-md text-sm text-on-surface-variant leading-relaxed">
                                    {{ vehicle.subtitle }}
                                </p>

                                <!-- Telemetry Spec Matrix Grid (4 Columns) -->
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-surface-container-highest/60">
                                    <div
                                        v-for="(spec, sIdx) in vehicle.specs"
                                        :key="sIdx"
                                        class="flex items-center gap-2.5 rounded-xl bg-surface-container-low p-3 text-on-surface"
                                    >
                                        <span class="material-symbols-outlined text-[22px] text-primary shrink-0">
                                            {{ spec.icon }}
                                        </span>
                                        <div class="flex flex-col min-w-0">
                                            <span class="font-spec-data text-[12px] font-semibold truncate">{{ spec.label }}</span>
                                            <span class="font-spec-data text-[10px] text-on-surface-variant">Spesifikasi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Vehicle Condition & Maintenance Notes (Kondisi Umum) -->
                            <div class="rounded-3xl bg-surface-container-lowest p-6 border border-surface-container-highest/70 shadow-sm space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-tertiary/10 text-tertiary flex items-center justify-center font-bold">
                                        <span class="material-symbols-outlined text-[22px]">health_and_safety</span>
                                    </div>
                                    <div>
                                        <h2 class="font-headline-sm text-lg font-bold text-on-surface">
                                            Kondisi Umum & Kebersihan Unit
                                        </h2>
                                        <p class="font-spec-data text-xs text-on-surface-variant">
                                            Standar perawatan ketat sebelum serah terima kunci
                                        </p>
                                    </div>
                                </div>

                                <div class="bg-surface-container-low rounded-2xl p-4 text-xs sm:text-sm text-on-surface leading-relaxed border border-surface-container-highest/60">
                                    <p>{{ vehicle.condition_notes || 'Unit dalam kondisi prima 100%, servis berkala rutin dealer resmi Astra/Yamaha, ban tubeless baru ganti, kampas rem tebal, body mulus, dan siap pakai jarak jauh maupun dalam kota.' }}</p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs text-on-surface-variant">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-tertiary text-[18px]">verified</span>
                                        <span>Inspeksi 40 Titik Mesin</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-tertiary text-[18px]">cleaning_services</span>
                                        <span>Cuci & Sanitasi Kabin</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-tertiary text-[18px]">shield</span>
                                        <span>Asuransi All-Risk Aktif</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Included Features & Touring Facilities -->
                            <div class="rounded-3xl bg-surface-container-lowest p-6 border border-surface-container-highest/70 shadow-sm space-y-4">
                                <h3 class="font-headline-sm text-base font-bold text-on-surface flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary text-[20px]">check_box</span>
                                    <span>Fitur & Fasilitas Gratis yang Termasuk</span>
                                </h3>

                                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs sm:text-sm text-on-surface-variant">
                                    <li
                                        v-for="(feat, fIdx) in vehicle.features"
                                        :key="fIdx"
                                        class="flex items-center gap-2 rounded-xl bg-surface-container-low p-2.5"
                                    >
                                        <span class="material-symbols-outlined text-tertiary text-[18px]">check_circle</span>
                                        <span>{{ feat }}</span>
                                    </li>
                                </ul>

                                <p v-if="vehicle.facilities" class="text-xs text-on-surface-variant bg-surface-container-low p-3.5 rounded-xl leading-relaxed">
                                    <strong class="text-on-surface">Paket Fasilitas:</strong> {{ vehicle.facilities }}
                                </p>
                            </div>

                            <!-- Rental Terms & Requirements (Persyaratan Penyewaan) -->
                            <div class="rounded-3xl bg-surface-container-lowest p-6 border border-surface-container-highest/70 shadow-sm space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold">
                                        <span class="material-symbols-outlined text-[22px]">assignment</span>
                                    </div>
                                    <div>
                                        <h3 class="font-headline-sm text-base font-bold text-on-surface">
                                            Rincian Persyaratan Penyewaan
                                        </h3>
                                        <p class="font-spec-data text-xs text-on-surface-variant">
                                            Verifikasi berkas 10-15 menit tanpa penahanan dokumen asli
                                        </p>
                                    </div>
                                </div>

                                <ul class="space-y-2.5 text-xs sm:text-sm text-on-surface-variant">
                                    <li
                                        v-for="(req, rIdx) in (vehicle.requirements || [
                                            'E-KTP Asli penyewa yang masih aktif',
                                            'SIM C / SIM A aktif yang sah dari Kepolisian',
                                            'Deposit jaminan Rp 50.000 - Rp 200.000 (Refundable 100% saat pengembalian)',
                                            'Denda keterlambatan Rp 15.000 - Rp 35.000 / jam (toleransi 30 menit dengan konfirmasi)'
                                        ])"
                                        :key="rIdx"
                                        class="flex items-start gap-2.5"
                                    >
                                        <span class="material-symbols-outlined text-[18px] text-tertiary shrink-0 mt-0.5">check_circle</span>
                                        <span>{{ req }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: Sticky Reactive Calculator & Online Booking Form -->
                        <div class="lg:col-span-5 sticky top-24 space-y-6">
                            
                            <!-- REACTIVE PRICE CALCULATOR CARD (Task 2.4 Requirement) -->
                            <div class="rounded-3xl bg-surface-container-lowest p-6 border border-surface-container-highest/80 shadow-lg space-y-6">
                                <div>
                                    <span class="font-label-badge text-xs uppercase tracking-wider text-primary font-bold">
                                        Kalkulator Sewa Transparan
                                    </span>
                                    <h3 class="font-headline-md text-xl font-bold text-on-surface mt-1">
                                        Estimasi Biaya Sewa
                                    </h3>
                                    <p class="font-body-sm text-xs text-on-surface-variant mt-0.5">
                                        Perhitungan otomatis dan pas tanpa biaya tersembunyi
                                    </p>
                                </div>

                                <!-- Step 1: Duration Type Tabs (Harian / Mingguan / Bulanan) -->
                                <div class="space-y-2">
                                    <label class="font-spec-data text-xs font-semibold text-on-surface flex items-center justify-between">
                                        <span>1. Pilih Tipe Periode:</span>
                                        <span v-if="durationPeriod === 'weekly'" class="text-tertiary font-bold text-[11px]">Diskon ~10%</span>
                                        <span v-if="durationPeriod === 'monthly'" class="text-tertiary font-bold text-[11px]">Diskon ~25%</span>
                                    </label>

                                    <div class="grid grid-cols-3 gap-1.5 rounded-xl bg-surface-container p-1 font-spec-data text-xs font-semibold">
                                        <button
                                            type="button"
                                            @click="durationPeriod = 'daily'"
                                            class="rounded-lg py-2 text-center transition-all"
                                            :class="[
                                                durationPeriod === 'daily'
                                                    ? 'bg-surface-container-lowest text-primary font-bold shadow-xs'
                                                    : 'text-on-surface-variant hover:text-on-surface'
                                            ]"
                                        >
                                            Harian
                                        </button>
                                        <button
                                            type="button"
                                            @click="durationPeriod = 'weekly'"
                                            class="rounded-lg py-2 text-center transition-all"
                                            :class="[
                                                durationPeriod === 'weekly'
                                                    ? 'bg-surface-container-lowest text-primary font-bold shadow-xs'
                                                    : 'text-on-surface-variant hover:text-on-surface'
                                            ]"
                                        >
                                            Mingguan
                                        </button>
                                        <button
                                            type="button"
                                            @click="durationPeriod = 'monthly'"
                                            class="rounded-lg py-2 text-center transition-all"
                                            :class="[
                                                durationPeriod === 'monthly'
                                                    ? 'bg-surface-container-lowest text-primary font-bold shadow-xs'
                                                    : 'text-on-surface-variant hover:text-on-surface'
                                            ]"
                                        >
                                            Bulanan
                                        </button>
                                    </div>
                                </div>

                                <!-- Step 2: Duration Stepper (1, 2, 3...) -->
                                <div class="space-y-2">
                                    <label class="font-spec-data text-xs font-semibold text-on-surface flex items-center justify-between">
                                        <span>2. Jumlah Durasi:</span>
                                        <span class="text-on-surface-variant text-[11px]">Minimal 1 {{ durationPeriodLabel }}</span>
                                    </label>

                                    <div class="flex items-center gap-3">
                                        <button
                                            type="button"
                                            @click="decrementDuration"
                                            class="w-11 h-11 rounded-xl bg-surface-container-high text-on-surface font-bold text-lg flex items-center justify-center hover:bg-surface-variant active:scale-95 transition cursor-pointer"
                                            aria-label="Kurangi durasi"
                                        >
                                            -
                                        </button>

                                        <div class="flex-1 flex items-center justify-center rounded-xl bg-surface-container-low border border-surface-container-highest py-2.5 px-4 font-headline-sm text-base font-bold text-primary">
                                            <span>{{ durationCount }} {{ durationPeriodLabel }}</span>
                                        </div>

                                        <button
                                            type="button"
                                            @click="incrementDuration"
                                            class="w-11 h-11 rounded-xl bg-surface-container-high text-on-surface font-bold text-lg flex items-center justify-center hover:bg-surface-variant active:scale-95 transition cursor-pointer"
                                            aria-label="Tambah durasi"
                                        >
                                            +
                                        </button>
                                    </div>
                                </div>

                                <!-- Step 3: Pickup Method (Ambil di Garasi Rp 0 / Antar-Jemput +Rp 25.000) -->
                                <div class="space-y-2">
                                    <label class="font-spec-data text-xs font-semibold text-on-surface">
                                        3. Metode Pengambilan & Serah Terima:
                                    </label>

                                    <div class="grid grid-cols-1 gap-2">
                                        <label
                                            class="flex items-start gap-3 p-3 rounded-2xl border transition-all cursor-pointer select-none"
                                            :class="[
                                                pickupMethod === 'garage'
                                                    ? 'bg-primary/5 border-primary ring-1 ring-primary/20'
                                                    : 'bg-surface-container-low border-surface-container-highest hover:bg-surface-variant/40'
                                            ]"
                                        >
                                            <input
                                                type="radio"
                                                v-model="pickupMethod"
                                                value="garage"
                                                class="mt-1 text-primary focus:ring-primary cursor-pointer"
                                            />
                                            <div class="flex-1 text-xs">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-bold text-on-surface">Ambil Sendiri di Garasi</span>
                                                    <span class="font-bold text-tertiary">Gratis (Rp 0)</span>
                                                </div>
                                                <p class="text-on-surface-variant text-[11px] mt-0.5">
                                                    Garasi Pusat Cilandak Jaksel, Hub Bandara Soetta, atau BSD City
                                                </p>
                                            </div>
                                        </label>

                                        <label
                                            class="flex items-start gap-3 p-3 rounded-2xl border transition-all cursor-pointer select-none"
                                            :class="[
                                                pickupMethod === 'delivery'
                                                    ? 'bg-primary/5 border-primary ring-1 ring-primary/20'
                                                    : 'bg-surface-container-low border-surface-container-highest hover:bg-surface-variant/40'
                                            ]"
                                        >
                                            <input
                                                type="radio"
                                                v-model="pickupMethod"
                                                value="delivery"
                                                class="mt-1 text-primary focus:ring-primary cursor-pointer"
                                            />
                                            <div class="flex-1 text-xs">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-bold text-on-surface">Layanan Antar-Jemput ke Lokasi</span>
                                                    <span class="font-bold text-primary">+Rp 25.000</span>
                                                </div>
                                                <p class="text-on-surface-variant text-[11px] mt-0.5">
                                                    Diantar on-time ke Hotel, Stasiun, Rumah, atau Kantor Anda se-Jabodetabek
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Step 4: Reactive Calculation Breakdown Matrix -->
                                <div class="rounded-2xl bg-surface-container-low p-4 space-y-2.5 border border-surface-container-highest/60 text-xs">
                                    <div class="flex items-center justify-between text-on-surface-variant">
                                        <span>Tarif Sewa ({{ durationCount }} × {{ formatRupiah(unitPrice) }}):</span>
                                        <span class="font-semibold text-on-surface">{{ formatRupiah(subtotalDurationPrice) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-on-surface-variant">
                                        <span>Biaya Pengantaran:</span>
                                        <span class="font-semibold text-on-surface">{{ formatRupiah(pickupFee) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-on-surface-variant">
                                        <span>Asuransi All-Risk:</span>
                                        <span class="font-semibold text-tertiary">Gratis / Termasuk</span>
                                    </div>

                                    <div class="pt-2 border-t border-surface-container-highest flex items-baseline justify-between">
                                        <div>
                                            <span class="block font-headline-sm text-xs font-bold text-on-surface">Total Estimasi Biaya</span>
                                            <span class="text-[10px] text-on-surface-variant">Bebas biaya tersembunyi</span>
                                        </div>
                                        <span class="font-headline-md text-xl font-extrabold text-primary">
                                            {{ formatRupiah(totalCalculatedPrice) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Step 5: Online Booking Form (Task 2.4 Requirement) -->
                                <form @submit="handleOnlineBookingSubmit" class="space-y-3.5 pt-2 border-t border-surface-container-highest">
                                    <h4 class="font-headline-sm text-sm font-bold text-on-surface">
                                        Data Pemesan Kendaraan:
                                    </h4>

                                    <!-- Nama Lengkap -->
                                    <div>
                                        <label class="block font-spec-data text-[11px] font-semibold text-on-surface mb-1">
                                            Nama Lengkap (sesuai KTP):
                                        </label>
                                        <input
                                            v-model="formFullName"
                                            type="text"
                                            required
                                            placeholder="Contoh: Budi Santoso"
                                            class="w-full rounded-xl bg-surface-container-low px-3.5 py-2.5 text-xs text-on-surface border border-surface-container-highest focus:border-primary focus:bg-surface-container-lowest focus:outline-none transition"
                                        />
                                    </div>

                                    <!-- Nomor WhatsApp -->
                                    <div>
                                        <label class="block font-spec-data text-[11px] font-semibold text-on-surface mb-1">
                                            Nomor WhatsApp Aktif:
                                        </label>
                                        <input
                                            v-model="formWhatsapp"
                                            type="tel"
                                            required
                                            placeholder="Contoh: 081234567890"
                                            class="w-full rounded-xl bg-surface-container-low px-3.5 py-2.5 text-xs text-on-surface border border-surface-container-highest focus:border-primary focus:bg-surface-container-lowest focus:outline-none transition"
                                        />
                                    </div>

                                    <!-- Kategori Pelanggan (Mahasiswa / Wisatawan / Pekerja / Umum) -->
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block font-spec-data text-[11px] font-semibold text-on-surface mb-1">
                                                Kategori Pelanggan:
                                            </label>
                                            <select
                                                v-model="formCategory"
                                                class="w-full rounded-xl bg-surface-container-low px-3 py-2 text-xs text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none transition cursor-pointer"
                                            >
                                                <option value="wisatawan">Wisatawan / Liburan</option>
                                                <option value="mahasiswa">Mahasiswa / Pelajar</option>
                                                <option value="pekerja">Pekerja / Dinas</option>
                                                <option value="umum">Pelanggan Umum</option>
                                            </select>
                                        </div>

                                        <!-- Tanggal Mulai -->
                                        <div>
                                            <label class="block font-spec-data text-[11px] font-semibold text-on-surface mb-1">
                                                Tanggal Mulai Sewa:
                                            </label>
                                            <input
                                                v-model="formStartDate"
                                                type="date"
                                                class="w-full rounded-xl bg-surface-container-low px-3 py-2 text-xs text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none transition"
                                            />
                                        </div>
                                    </div>

                                    <!-- Catatan Tambahan / Alamat Pengantaran -->
                                    <div>
                                        <label class="block font-spec-data text-[11px] font-semibold text-on-surface mb-1">
                                            Alamat Pengantaran / Catatan Khusus:
                                        </label>
                                        <textarea
                                            v-model="formNotes"
                                            rows="2"
                                            placeholder="Tulis alamat hotel / stasiun / request 2 helm fullface..."
                                            class="w-full rounded-xl bg-surface-container-low p-3 text-xs text-on-surface border border-surface-container-highest focus:border-primary focus:bg-surface-container-lowest focus:outline-none transition"
                                        ></textarea>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="pt-2 flex flex-col gap-2">
                                        <!-- WhatsApp Primary Action -->
                                        <a
                                            :href="getWhatsAppBookingUrl()"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="w-full py-3.5 px-4 rounded-xl bg-primary text-on-primary font-title-md text-xs sm:text-sm font-bold text-center shadow-md hover:bg-primary-container transition active:scale-[0.99] flex items-center justify-center gap-2"
                                        >
                                            <span class="material-symbols-outlined text-[20px]">chat</span>
                                            <span>Pesan Sekarang via WhatsApp</span>
                                        </a>

                                        <!-- Online Form Submit Button -->
                                        <button
                                            type="submit"
                                            class="w-full py-2.5 px-4 rounded-xl bg-surface-container-high text-primary font-title-md text-xs font-semibold text-center hover:bg-surface-variant transition active:scale-[0.99]"
                                        >
                                            Kirim Formulir Reservasi Online
                                        </button>
                                    </div>

                                    <div class="flex items-center justify-center gap-3 pt-1 text-[11px] text-on-surface-variant text-center font-spec-data">
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px] text-tertiary">bolt</span>
                                            Respon WhatsApp 15 Menit
                                        </span>
                                        <span>•</span>
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px] text-tertiary">shield</span>
                                            Proteksi All-Risk
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- RELATED FLEET SECTION (Rekomendasi Armada Lainnya) -->
                    <div v-if="relatedVehicles.length > 0" class="mt-20 pt-12 border-t border-surface-container-highest/60">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <span class="font-label-badge text-xs uppercase tracking-wider text-primary font-bold">
                                    Pilihan Lainnya
                                </span>
                                <h3 class="font-headline-lg text-2xl font-bold text-on-surface mt-1">
                                    Rekomendasi Armada Serupa
                                </h3>
                            </div>
                            <Link href="/katalog" class="text-primary hover:underline font-title-md text-xs font-semibold flex items-center gap-1">
                                <span>Lihat Semua Unit</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </Link>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <VehicleCard
                                v-for="rel in relatedVehicles"
                                :key="rel.id"
                                :vehicle="rel"
                            />
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- SUCCESS RESERVATION MODAL -->
        <div
            v-if="showBookingSuccessModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        >
            <div class="relative w-full max-w-md rounded-3xl bg-surface-container-lowest p-6 shadow-2xl border border-surface-container-highest text-center space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-tertiary/10 text-tertiary flex items-center justify-center mx-auto">
                    <span class="material-symbols-outlined text-[36px]">check_circle</span>
                </div>

                <h3 class="font-headline-sm text-xl font-bold text-on-surface">
                    Formulir Booking Diterima!
                </h3>

                <p class="font-body-md text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                    Terima kasih, <strong>{{ formFullName }}</strong>. Rincian sewa unit <strong>{{ vehicle.name }}</strong> (Total: <strong>{{ formatRupiah(totalCalculatedPrice) }}</strong>) telah dicatat oleh sistem.
                </p>

                <div class="rounded-2xl bg-surface-container-low p-3.5 text-xs text-left text-on-surface-variant space-y-1">
                    <p>• Unit: <strong>{{ vehicle.name }}</strong></p>
                    <p>• Durasi: <strong>{{ durationCount }} {{ durationPeriodLabel }}</strong></p>
                    <p>• Pengambilan: <strong>{{ pickupMethod === 'delivery' ? 'Antar-Jemput (+Rp 25.000)' : 'Ambil di Garasi (Gratis)' }}</strong></p>
                </div>

                <div class="pt-2 flex flex-col gap-2">
                    <a
                        :href="getWhatsAppBookingUrl()"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="w-full py-3 rounded-xl bg-primary text-on-primary font-title-md text-xs font-bold text-center hover:bg-primary-container shadow-md transition"
                    >
                        Lanjutkan Konfirmasi ke WhatsApp Admin
                    </a>
                    <button
                        type="button"
                        @click="showBookingSuccessModal = false"
                        class="w-full py-2.5 rounded-xl bg-surface-container-high text-on-surface font-title-md text-xs font-semibold hover:bg-surface-variant transition"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
