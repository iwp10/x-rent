<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({
    initialCode: {
        type: String,
        default: '',
    },
    activeBooking: {
        type: Object,
        required: true,
    },
    allBookings: {
        type: Array,
        default: () => [],
    },
    sampleCodes: {
        type: Array,
        default: () => [],
    },
    faqs: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();

// Search Input State
const searchInput = ref(props.initialCode || props.activeBooking.code || '');
const currentBooking = ref(props.activeBooking);
const isNotFound = ref(false);

// Toast Notification State
const toastMessage = ref('');
const toastTitle = ref('');
const toastType = ref('success'); // 'success' | 'info' | 'error'
const showToast = ref(false);
let toastTimer = null;

const triggerToast = (title, message, type = 'success') => {
    toastTitle.value = title;
    toastMessage.value = message;
    toastType.value = type;
    showToast.value = true;
    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
        showToast.value = false;
    }, 4000);
};

// Check for flash success message on mount (from BookingController@store)
onMounted(() => {
    if (page.props.flash && page.props.flash.success) {
        triggerToast('Pemesanan Berhasil!', page.props.flash.success, 'success');
    }
});

// Format Rupiah
const formatRupiah = (val) => {
    if (val === undefined || val === null) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(val);
};

// Search booking action
const handleSearch = () => {
    const query = searchInput.value.trim().toUpperCase();
    if (!query) {
        triggerToast('Perhatian', 'Silakan masukkan Kode Booking terlebih dahulu.', 'info');
        return;
    }

    const found = props.allBookings.find(
        (b) => b.code.toUpperCase() === query
    );

    if (found) {
        currentBooking.value = found;
        isNotFound.value = false;
        triggerToast(
            'Data Booking Ditemukan',
            `Menampilkan status pesanan ${found.code} (${found.vehicle.name})`,
            'success'
        );
        // Sync URL query without full reload
        router.get(
            '/cek-pesanan',
            { code: found.code },
            { preserveState: true, replace: true, preserveScroll: true }
        );
    } else {
        isNotFound.value = true;
        triggerToast(
            'Kode Tidak Ditemukan',
            `Kode booking "${query}" tidak terdaftar di sistem kami. Coba klik salah satu sampel di bawah.`,
            'error'
        );
    }
};

// Quick Sample Click
const selectSampleBooking = (sampleCode) => {
    searchInput.value = sampleCode;
    const found = props.allBookings.find((b) => b.code === sampleCode);
    if (found) {
        currentBooking.value = found;
        isNotFound.value = false;
        triggerToast(
            'Sampel Dimuat',
            `Beralih ke status "${found.status_label}" untuk unit ${found.vehicle.name}`,
            'info'
        );
        router.get(
            '/cek-pesanan',
            { code: sampleCode },
            { preserveState: true, replace: true, preserveScroll: true }
        );
    }
};

// Copy Booking Code
const copyBookingCode = () => {
    if (!currentBooking.value) return;
    navigator.clipboard.writeText(currentBooking.value.code);
    triggerToast(
        'Berhasil Disalin!',
        `Kode booking ${currentBooking.value.code} telah disalin ke papan klip.`,
        'success'
    );
};

// WhatsApp Inquiry for Current Booking
const getWhatsAppInquiryUrl = () => {
    if (page.props.flash && page.props.flash.whatsapp_url) {
        return page.props.flash.whatsapp_url;
    }
    if (!currentBooking.value) return 'https://wa.me/6281288889999';
    const b = currentBooking.value;
    const msg = `Halo Admin X-RENT, saya ingin menanyakan status pesanan saya dengan Kode Booking: *${b.code}* atas nama *${b.customer_name}* untuk unit *${b.vehicle.name}*. Mohon informasinya. Terima kasih!`;
    return `https://wa.me/6281288889999?text=${encodeURIComponent(msg)}`;
};

// Status Visual Config
const statusConfig = computed(() => {
    if (!currentBooking.value) return { bg: 'bg-slate-100', text: 'text-slate-800', border: 'border-slate-300' };
    const status = currentBooking.value.status;
    switch (status) {
        case 'pending':
            return {
                bg: 'bg-amber-500/10',
                text: 'text-amber-700',
                border: 'border-amber-500/30',
                dot: 'bg-amber-500',
                label: 'Menunggu Konfirmasi',
                icon: 'hourglass_top',
            };
        case 'confirmed':
            return {
                bg: 'bg-blue-500/10',
                text: 'text-blue-700',
                border: 'border-blue-500/30',
                dot: 'bg-blue-500',
                label: 'Pesanan Dikonfirmasi',
                icon: 'check_circle',
            };
        case 'ongoing':
            return {
                bg: 'bg-emerald-500/10',
                text: 'text-emerald-700',
                border: 'border-emerald-500/30',
                dot: 'bg-emerald-500',
                label: 'Sedang Disewa',
                icon: 'directions_car',
            };
        case 'completed':
            return {
                bg: 'bg-slate-500/10',
                text: 'text-slate-700',
                border: 'border-slate-500/30',
                dot: 'bg-slate-500',
                label: 'Selesai & Direfund',
                icon: 'verified',
            };
        default:
            return {
                bg: 'bg-primary/10',
                text: 'text-primary',
                border: 'border-primary/30',
                dot: 'bg-primary',
                label: currentBooking.value.status_label,
                icon: 'info',
            };
    }
});
</script>

<template>
    <Head title="Cek Status Pemesanan (Track Booking) | X-RENT" />

    <MainLayout>
        <div class="flex flex-col w-full bg-surface pb-20">
            <!-- BREADCRUMB & HEADER SECTION -->
            <section class="w-full bg-surface-container-low border-b border-surface-container-highest/60 py-4">
                <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                    <nav class="flex items-center gap-2 text-xs font-spec-data text-on-surface-variant" aria-label="Breadcrumb">
                        <Link href="/" class="hover:text-primary transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">home</span>
                            <span>Beranda</span>
                        </Link>
                        <span class="text-outline-variant">/</span>
                        <span class="text-primary font-bold">Cek Status Pemesanan</span>
                    </nav>
                </div>
            </section>

            <!-- TRACKING HERO & SEARCH BAR -->
            <section class="w-full pt-8 pb-10 bg-gradient-to-b from-surface-container-low/70 to-surface">
                <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto space-y-3">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-primary/10 text-primary border border-primary/20 text-xs font-label-badge font-bold uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[16px]">qr_code_scanner</span>
                            <span>Lacak Status Sewa Real-Time</span>
                        </div>
                        <h1 class="font-headline-lg text-2xl sm:text-4xl font-extrabold text-on-surface tracking-tight">
                            Cek Status Pemesanan Armada
                        </h1>
                        <p class="font-body-md text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                            Masukkan Kode Booking Anda untuk memantau proses verifikasi berkas, persiapan unit di garasi, jadwal serah kunci, hingga status pengembalian deposit.
                        </p>
                    </div>

                    <!-- SEARCH INPUT BOX -->
                    <div class="mt-8 max-w-xl mx-auto">
                        <form @submit.prevent="handleSearch" class="relative flex items-center">
                            <span class="absolute left-4 text-on-surface-variant material-symbols-outlined text-[22px] pointer-events-none">
                                search
                            </span>
                            <input
                                v-model="searchInput"
                                type="text"
                                placeholder="Masukkan Kode Booking (Contoh: XR-20261014-AB12)..."
                                class="w-full pl-12 pr-32 py-3.5 sm:py-4 rounded-2xl bg-surface-container-lowest text-on-surface text-xs sm:text-sm font-semibold border-2 border-surface-container-highest focus:border-primary focus:outline-none shadow-sm transition"
                            />
                            <button
                                type="submit"
                                class="absolute right-2 px-4 sm:px-5 py-2.5 rounded-xl bg-primary text-on-primary font-title-md text-xs sm:text-sm font-bold hover:bg-primary-container shadow-sm transition active:scale-95 flex items-center gap-1.5"
                            >
                                <span>Lacak</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </button>
                        </form>

                        <!-- QUICK SAMPLE PILLS -->
                        <div class="mt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs font-spec-data text-on-surface-variant">
                            <span class="font-bold flex items-center gap-1 shrink-0">
                                <span class="material-symbols-outlined text-[16px] text-primary">science</span>
                                <span>Coba Contoh Kode:</span>
                            </span>
                            <div class="flex flex-wrap items-center gap-1.5">
                                <button
                                    v-for="sample in sampleCodes"
                                    :key="sample.code"
                                    type="button"
                                    @click="selectSampleBooking(sample.code)"
                                    class="px-2.5 py-1 rounded-lg text-[11px] font-semibold transition border"
                                    :class="[
                                        currentBooking?.code === sample.code && !isNotFound
                                            ? 'bg-primary text-white border-primary shadow-xs'
                                            : 'bg-surface-container-lowest text-on-surface border-surface-container-highest hover:border-primary/50'
                                    ]"
                                    :title="`${sample.badge} (${sample.vehicle})`"
                                >
                                    {{ sample.label }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- NOT FOUND STATE -->
            <section v-if="isNotFound" class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="max-w-xl mx-auto p-8 rounded-3xl bg-surface-container-lowest border border-red-200 text-center space-y-4 shadow-sm">
                    <div class="w-16 h-16 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center mx-auto">
                        <span class="material-symbols-outlined text-[36px]">search_off</span>
                    </div>
                    <h2 class="font-headline-sm text-lg font-bold text-on-surface">
                        Kode Booking Tidak Ditemukan
                    </h2>
                    <p class="font-body-md text-xs text-on-surface-variant leading-relaxed">
                        Kode <strong>{{ searchInput }}</strong> belum tercatat di sistem kami. Pastikan format penulisan benar seperti <strong>XR-20261014-AB12</strong> atau hubungi customer service kami jika baru saja melakukan pemesanan via telepon.
                    </p>
                    <div class="pt-2 flex items-center justify-center gap-3">
                        <button
                            type="button"
                            @click="selectSampleBooking('XR-20261014-AB12')"
                            class="px-4 py-2 rounded-xl bg-primary text-on-primary font-title-md text-xs font-bold hover:bg-primary-container transition"
                        >
                            Tampilkan Contoh Aktif
                        </button>
                        <a
                            href="https://wa.me/6281288889999"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="px-4 py-2 rounded-xl bg-surface-container-high text-on-surface font-title-md text-xs font-semibold hover:bg-surface-variant transition flex items-center gap-1.5"
                        >
                            <span class="material-symbols-outlined text-[16px]">chat</span>
                            <span>Chat CS Bantuan</span>
                        </a>
                    </div>
                </div>
            </section>

            <!-- ACTIVE BOOKING RESULT CONTENT -->
            <section v-else-if="currentBooking" class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 mt-4 space-y-8">
                <!-- TOP STATUS BANNER CARD -->
                <div class="rounded-3xl bg-surface-container-lowest p-6 sm:p-8 border border-surface-container-highest shadow-sm">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-surface-container-highest/60">
                        <div class="space-y-2">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="font-title-md text-xs font-bold text-on-surface-variant uppercase tracking-wider">
                                    Kode Reservasi:
                                </span>
                                <div class="inline-flex items-center gap-2 bg-surface-container-low px-3.5 py-1.5 rounded-xl border border-surface-container-highest">
                                    <span class="font-spec-data text-sm sm:text-base font-extrabold text-primary tracking-wide">
                                        {{ currentBooking.code }}
                                    </span>
                                    <button
                                        type="button"
                                        @click="copyBookingCode"
                                        class="text-on-surface-variant hover:text-primary transition p-0.5"
                                        title="Salin Kode Booking"
                                    >
                                        <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                    </button>
                                </div>
                                <span class="text-xs text-on-surface-variant font-spec-data">
                                    • Dibuat pada: {{ currentBooking.created_at }}
                                </span>
                            </div>

                            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                                {{ currentBooking.status_desc }}
                            </p>
                        </div>

                        <!-- Status Pill Badge -->
                        <div class="shrink-0 flex items-center gap-3">
                            <div
                                class="inline-flex items-center gap-2.5 px-4 py-2 rounded-2xl border font-title-md text-xs sm:text-sm font-bold shadow-xs"
                                :class="[statusConfig.bg, statusConfig.text, statusConfig.border]"
                            >
                                <span class="w-2.5 h-2.5 rounded-full" :class="[statusConfig.dot, 'animate-pulse']"></span>
                                <span class="material-symbols-outlined text-[18px]">{{ statusConfig.icon }}</span>
                                <span>{{ currentBooking.status_label }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- 4-STEP PROGRESS STEPPER (GOOGLE STITCH REFERENCE) -->
                    <div class="mt-8 pt-2">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="font-title-md text-sm font-bold text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px] text-primary">linear_scale</span>
                                <span>Tahapan Proses Penyewaan (Progress Stepper)</span>
                            </h2>
                            <span class="font-spec-data text-xs text-on-surface-variant font-semibold">
                                Langkah {{ currentBooking.step_index }} dari 4
                            </span>
                        </div>

                        <!-- Desktop & Tablet Stepper Bar -->
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 relative">
                            <div
                                v-for="(timelineStep, sIdx) in currentBooking.timeline"
                                :key="timelineStep.step"
                                class="relative rounded-2xl p-4 border transition-all"
                                :class="[
                                    timelineStep.status === 'completed'
                                        ? 'bg-primary/5 border-primary/30 shadow-xs'
                                        : timelineStep.status === 'current'
                                        ? 'bg-surface-container-lowest border-2 border-primary shadow-md ring-2 ring-primary/10'
                                        : 'bg-surface-container-low/50 border-surface-container-highest/60 opacity-60'
                                ]"
                            >
                                <div class="flex items-start gap-3">
                                    <!-- Step Circle Indicator -->
                                    <div
                                        class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 transition"
                                        :class="[
                                            timelineStep.status === 'completed'
                                                ? 'bg-primary text-white shadow-xs'
                                                : timelineStep.status === 'current'
                                                ? 'bg-primary text-white animate-pulse'
                                                : 'bg-surface-container-high text-on-surface-variant'
                                        ]"
                                    >
                                        <span v-if="timelineStep.status === 'completed'" class="material-symbols-outlined text-[18px]">
                                            check
                                        </span>
                                        <span v-else class="material-symbols-outlined text-[18px]">
                                            {{ timelineStep.icon }}
                                        </span>
                                    </div>

                                    <!-- Step Details -->
                                    <div class="space-y-1 min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-label-badge text-[10px] uppercase font-bold text-primary">
                                                Langkah {{ timelineStep.step }}
                                            </span>
                                            <span
                                                v-if="timelineStep.status === 'current'"
                                                class="px-1.5 py-0.5 rounded-full bg-primary/10 text-primary text-[9px] font-bold"
                                            >
                                                Aktif
                                            </span>
                                        </div>
                                        <h3 class="font-title-md text-xs font-bold text-on-surface truncate">
                                            {{ timelineStep.name }}
                                        </h3>
                                        <p class="font-body-sm text-[11px] text-on-surface-variant line-clamp-2 leading-relaxed">
                                            {{ timelineStep.desc }}
                                        </p>
                                        <p class="font-spec-data text-[10px] font-semibold text-primary pt-1">
                                            {{ timelineStep.time }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MAIN DETAILS GRID (2 COLUMNS) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <!-- LEFT COLUMN: VEHICLE & RENTER SPECS (7 COLS) -->
                    <div class="lg:col-span-7 space-y-6">
                        <!-- VEHICLE INFORMATION CARD -->
                        <div class="rounded-3xl bg-surface-container-lowest p-6 sm:p-7 border border-surface-container-highest shadow-sm space-y-5">
                            <div class="flex items-center justify-between">
                                <h3 class="font-title-md text-sm font-bold text-on-surface flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[20px] text-primary">directions_car</span>
                                    <span>Unit Armada yang Dipesan</span>
                                </h3>
                                <Link
                                    :href="`/armada/${currentBooking.vehicle.slug}`"
                                    class="text-primary hover:underline text-xs font-bold flex items-center gap-1"
                                >
                                    <span>Lihat Spesifikasi</span>
                                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                </Link>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center gap-5 p-4 rounded-2xl bg-surface-container-low border border-surface-container-highest/60">
                                <img
                                    :src="currentBooking.vehicle.image"
                                    :alt="currentBooking.vehicle.name"
                                    class="w-full sm:w-36 h-28 object-cover rounded-xl shadow-xs"
                                />
                                <div class="space-y-1.5 flex-1 text-center sm:text-left w-full">
                                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                        <span class="px-2.5 py-0.5 rounded-lg bg-primary/10 text-primary font-bold text-[10px] uppercase">
                                            {{ currentBooking.vehicle.type === 'mobil' ? 'Armada Mobil' : 'Armada Motor' }}
                                        </span>
                                        <span class="px-2.5 py-0.5 rounded-lg bg-surface-container-high text-on-surface font-spec-data font-bold text-[11px]">
                                            Plat: {{ currentBooking.vehicle.plate }}
                                        </span>
                                    </div>
                                    <h4 class="font-headline-sm text-base sm:text-lg font-bold text-on-surface">
                                        {{ currentBooking.vehicle.name }}
                                    </h4>
                                    <p class="font-body-sm text-xs text-on-surface-variant">
                                        Transmisi: <strong>{{ currentBooking.vehicle.transmission }}</strong> • Brand: <strong>{{ currentBooking.vehicle.brand }}</strong>
                                    </p>
                                </div>
                            </div>

                            <!-- RENTER DETAILS -->
                            <div class="pt-2 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-spec-data">
                                <div class="p-3.5 rounded-xl bg-surface-container-low/70 border border-surface-container-highest/40 space-y-1">
                                    <span class="text-on-surface-variant font-medium">Nama Penyewa:</span>
                                    <p class="font-bold text-on-surface text-sm">{{ currentBooking.customer_name }}</p>
                                </div>
                                <div class="p-3.5 rounded-xl bg-surface-container-low/70 border border-surface-container-highest/40 space-y-1">
                                    <span class="text-on-surface-variant font-medium">Nomor WhatsApp:</span>
                                    <p class="font-bold text-primary text-sm">{{ currentBooking.customer_phone }}</p>
                                </div>
                                <div class="p-3.5 rounded-xl bg-surface-container-low/70 border border-surface-container-highest/40 space-y-1">
                                    <span class="text-on-surface-variant font-medium">Kategori Pelanggan:</span>
                                    <p class="font-bold text-on-surface">{{ currentBooking.customer_category }}</p>
                                </div>
                                <div class="p-3.5 rounded-xl bg-surface-container-low/70 border border-surface-container-highest/40 space-y-1">
                                    <span class="text-on-surface-variant font-medium">Metode Pengambilan:</span>
                                    <p class="font-bold text-tertiary">{{ currentBooking.pickup.label }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- SCHEDULE & LOCATION DETAILS -->
                        <div class="rounded-3xl bg-surface-container-lowest p-6 sm:p-7 border border-surface-container-highest shadow-sm space-y-5">
                            <h3 class="font-title-md text-sm font-bold text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] text-primary">schedule</span>
                                <span>Jadwal & Lokasi Pengambilan</span>
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="p-4 rounded-2xl bg-surface-container-low border border-surface-container-highest/60 space-y-1">
                                    <div class="flex items-center gap-1.5 text-xs text-primary font-bold">
                                        <span class="material-symbols-outlined text-[16px]">flight_takeoff</span>
                                        <span>Mulai Sewa:</span>
                                    </div>
                                    <p class="font-spec-data text-sm font-bold text-on-surface">
                                        {{ currentBooking.rental_period.start_datetime }}
                                    </p>
                                    <span class="text-[11px] text-on-surface-variant">Durasi: {{ currentBooking.rental_period.duration_label }}</span>
                                </div>

                                <div class="p-4 rounded-2xl bg-surface-container-low border border-surface-container-highest/60 space-y-1">
                                    <div class="flex items-center gap-1.5 text-xs text-tertiary font-bold">
                                        <span class="material-symbols-outlined text-[16px]">flight_land</span>
                                        <span>Pengembalian Unit:</span>
                                    </div>
                                    <p class="font-spec-data text-sm font-bold text-on-surface">
                                        {{ currentBooking.rental_period.end_datetime }}
                                    </p>
                                    <span class="text-[11px] text-on-surface-variant">Toleransi overtime: 60 menit gratis</span>
                                </div>
                            </div>

                            <!-- Address Box -->
                            <div class="p-4 rounded-2xl bg-surface-container-low/70 border border-surface-container-highest/50 space-y-2">
                                <div class="flex items-center gap-2 text-xs font-bold text-on-surface">
                                    <span class="material-symbols-outlined text-[18px] text-primary">location_on</span>
                                    <span>Alamat Serah Terima Kunci:</span>
                                </div>
                                <p class="font-body-md text-xs text-on-surface-variant leading-relaxed">
                                    {{ currentBooking.pickup.address }}
                                </p>
                                <div class="pt-1 flex items-center gap-2 text-xs font-spec-data text-on-surface">
                                    <span class="material-symbols-outlined text-[16px] text-tertiary">support_agent</span>
                                    <span class="font-semibold">{{ currentBooking.pickup.contact_person }}</span>
                                </div>
                            </div>

                            <!-- Catatan Pelanggan -->
                            <div v-if="currentBooking.notes" class="p-3.5 rounded-xl bg-surface-container-low text-xs text-on-surface-variant space-y-1">
                                <span class="font-bold text-on-surface">Catatan Tambahan dari Anda:</span>
                                <p class="italic">"{{ currentBooking.notes }}"</p>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: FINANCIAL BREAKDOWN & ACTION CTAS (5 COLS) -->
                    <div class="lg:col-span-5 space-y-6">
                        <!-- FINANCIAL SUMMARY CARD -->
                        <div class="rounded-3xl bg-surface-container-lowest p-6 sm:p-7 border border-surface-container-highest shadow-sm space-y-5">
                            <h3 class="font-title-md text-sm font-bold text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] text-primary">receipt_long</span>
                                <span>Rincian Biaya & Pembayaran</span>
                            </h3>

                            <div class="space-y-3 font-spec-data text-xs text-on-surface-variant">
                                <div class="flex items-center justify-between pb-2 border-b border-surface-container-highest/50">
                                    <span>Tarif Sewa ({{ currentBooking.rental_period.duration_label }}):</span>
                                    <span class="font-semibold text-on-surface">{{ formatRupiah(currentBooking.payment.subtotal_rent) }}</span>
                                </div>

                                <div class="flex items-center justify-between pb-2 border-b border-surface-container-highest/50">
                                    <span>Biaya Pengantaran / Logistik:</span>
                                    <span class="font-semibold text-on-surface">
                                        {{ currentBooking.payment.delivery_fee > 0 ? formatRupiah(currentBooking.payment.delivery_fee) : 'Gratis (Rp 0)' }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between pb-2 border-b border-surface-container-highest/50">
                                    <span class="flex items-center gap-1">
                                        <span>Deposit Jaminan (Refundable):</span>
                                        <span class="text-tertiary" title="Dikembalikan 100% saat unit kembali aman">*</span>
                                    </span>
                                    <span class="font-semibold text-on-surface">{{ formatRupiah(currentBooking.payment.deposit_amount) }}</span>
                                </div>

                                <div class="flex items-center justify-between pb-2 border-b border-surface-container-highest/50">
                                    <span>Proteksi Asuransi All-Risk:</span>
                                    <span class="font-bold text-tertiary">Termasuk (Gratis)</span>
                                </div>

                                <!-- Grand Total -->
                                <div class="pt-2 flex items-baseline justify-between text-on-surface">
                                    <span class="font-title-md text-sm font-bold">Total Nilai Sewa:</span>
                                    <span class="font-headline-sm text-xl font-extrabold text-primary">
                                        {{ formatRupiah(currentBooking.payment.total_payable) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Payment Status Box -->
                            <div class="p-4 rounded-2xl bg-surface-container-low border border-surface-container-highest/60 space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-spec-data text-on-surface-variant font-medium">Status Pembayaran:</span>
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[11px] font-bold"
                                        :class="currentBooking.payment.is_paid ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'"
                                    >
                                        {{ currentBooking.payment.status }}
                                    </span>
                                </div>
                                <p class="font-body-sm text-[11px] text-on-surface-variant">
                                    Metode: {{ currentBooking.payment.method }}
                                </p>
                            </div>

                            <!-- ACTION BUTTONS -->
                            <div class="space-y-3 pt-2">
                                <a
                                    :href="getWhatsAppInquiryUrl()"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="w-full py-3.5 px-4 rounded-xl bg-primary text-on-primary font-title-md text-xs sm:text-sm font-bold text-center shadow-md hover:bg-primary-container transition active:scale-[0.99] flex items-center justify-center gap-2"
                                >
                                    <span class="material-symbols-outlined text-[20px]">chat</span>
                                    <span>Konfirmasi CS via WhatsApp</span>
                                </a>

                                <button
                                    type="button"
                                    @click="triggerToast('Mencetak Dokumen', 'Mempersiapkan bukti reservasi digital untuk diunduh...', 'info')"
                                    class="w-full py-2.5 px-4 rounded-xl bg-surface-container-high text-on-surface font-title-md text-xs font-semibold text-center hover:bg-surface-variant transition flex items-center justify-center gap-2"
                                >
                                    <span class="material-symbols-outlined text-[18px]">download</span>
                                    <span>Unduh Bukti Reservasi (PDF)</span>
                                </button>
                            </div>

                            <!-- Trust Badges -->
                            <div class="pt-2 border-t border-surface-container-highest/50 flex flex-col gap-2 font-spec-data text-[11px] text-on-surface-variant">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-tertiary text-[16px]">verified</span>
                                    <span>Jaminan Deposit dikembalikan utuh tanpa potongan sepihak</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary text-[16px]">support_agent</span>
                                    <span>Dukungan CS Siaga 24 Jam siap membantu kendala di jalan</span>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ ACCORDION SUMMARY BOX (STITCH REFERENCE) -->
                        <div class="rounded-3xl bg-surface-container-lowest p-6 border border-surface-container-highest shadow-sm space-y-4">
                            <h3 class="font-title-md text-sm font-bold text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] text-tertiary">help</span>
                                <span>Pertanyaan Seputar Pesanan</span>
                            </h3>

                            <div class="space-y-3">
                                <div
                                    v-for="(faq, fIdx) in faqs"
                                    :key="fIdx"
                                    class="p-3.5 rounded-xl bg-surface-container-low space-y-1 text-xs"
                                >
                                    <p class="font-title-md font-bold text-on-surface">
                                        {{ faq.q }}
                                    </p>
                                    <p class="font-body-sm text-on-surface-variant leading-relaxed">
                                        {{ faq.a }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- NOTIFICATION TOAST CONTAINER (STITCH REFERENCE) -->
            <transition
                enter-active-class="transform transition ease-out duration-300"
                enter-from-class="translate-x-full opacity-0"
                enter-to-class="translate-x-0 opacity-100"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="translate-x-0 opacity-100"
                leave-to-class="translate-x-full opacity-0"
            >
                <div
                    v-if="showToast"
                    class="fixed top-24 right-6 z-50 pointer-events-auto max-w-sm"
                >
                    <div
                        class="flex items-start gap-3 bg-surface-container-lowest text-on-surface p-4 rounded-2xl shadow-2xl border-l-4"
                        :class="[
                            toastType === 'success'
                                ? 'border-tertiary'
                                : toastType === 'error'
                                ? 'border-red-500'
                                : 'border-primary'
                        ]"
                    >
                        <span
                            class="material-symbols-outlined text-2xl shrink-0 mt-0.5"
                            :class="[
                                toastType === 'success'
                                    ? 'text-tertiary'
                                    : toastType === 'error'
                                    ? 'text-red-500'
                                    : 'text-primary'
                            ]"
                        >
                            {{ toastType === 'success' ? 'check_circle' : toastType === 'error' ? 'error' : 'info' }}
                        </span>
                        <div class="space-y-0.5 min-w-0">
                            <p class="font-title-md text-xs sm:text-sm font-bold text-on-surface">
                                {{ toastTitle }}
                            </p>
                            <p class="font-body-sm text-xs text-on-surface-variant leading-relaxed">
                                {{ toastMessage }}
                            </p>
                        </div>
                        <button
                            type="button"
                            @click="showToast = false"
                            class="text-on-surface-variant hover:text-on-surface transition p-0.5 ml-auto"
                        >
                            <span class="material-symbols-outlined text-[16px]">close</span>
                        </button>
                    </div>
                </div>
            </transition>
        </div>
    </MainLayout>
</template>
