<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import VehicleCard from '@/Components/VehicleCard.vue';

const props = defineProps({
    vehicles: {
        type: Array,
        default: () => [],
    },
    initialType: {
        type: String,
        default: 'all', // 'all' | 'motor' | 'mobil'
    },
    initialCategory: {
        type: String,
        default: 'all',
    },
    stats: {
        type: Object,
        default: () => ({
            total_units: '10+ Unit',
            service_guarantee: '100% Prima',
            insurance: 'All-Risk',
        }),
    },
    categories: {
        type: Array,
        default: () => [
            { id: 'all', label: 'Semua Kategori' },
            { id: 'matic-ekonomis', label: 'Matic Ekonomis (110-125cc)' },
            { id: 'matic-maxi', label: 'Maxi Scooter (150-160cc)' },
            { id: 'mobil-keluarga', label: 'MPV & Mobil Keluarga' },
            { id: 'hybrid-listrik', label: 'Hybrid & Elektrik' },
        ],
    },
});

// Reactive States for Catalog Filtering & Controls (Vue 3 ref)
const searchQuery = ref('');
const vehicleTypeFilter = ref(props.initialType || 'all'); // 'all' | 'motor' | 'mobil'
const categoryFilter = ref(props.initialCategory || 'all');
const transmissionFilter = ref('all'); // 'all' | 'matic' | 'manual'
const onlyAvailable = ref(false);
const sortBy = ref('popular'); // 'popular' | 'price-asc' | 'price-desc' | 'year'
const pricePeriod = ref('daily'); // 'daily' | 'weekly' | 'monthly'

// Modal Detail State
const selectedDetailVehicle = ref(null);
const selectedDetailPrice = ref(0);
const selectedDetailPeriod = ref('daily');

// Computed Filtered and Sorted Vehicles (Vue 3 computed)
const filteredVehicles = computed(() => {
    let list = [...props.vehicles];

    // 1. Filter by Vehicle Type (Motor vs Mobil vs Semua)
    if (vehicleTypeFilter.value !== 'all') {
        list = list.filter((v) => v.type === vehicleTypeFilter.value);
    }

    // 2. Filter by Sub-Category
    if (categoryFilter.value !== 'all') {
        list = list.filter((v) => v.category === categoryFilter.value);
    }

    // 3. Filter by Transmission (Matic vs Manual)
    if (transmissionFilter.value !== 'all') {
        list = list.filter((v) => {
            const trans = v.transmission?.toLowerCase() || '';
            if (transmissionFilter.value === 'matic') {
                return trans.includes('matic') || trans.includes('cvt') || trans.includes('otomatis');
            }
            if (transmissionFilter.value === 'manual') {
                return trans.includes('manual');
            }
            return true;
        });
    }

    // 4. Filter by availability (status === 'available')
    if (onlyAvailable.value) {
        list = list.filter((v) => v.status === 'available' || (v.status === undefined && v.is_available === true));
    }

    // 5. Filter by search query (name, brand, category_label, subtitle, features)
    if (searchQuery.value.trim() !== '') {
        const query = searchQuery.value.toLowerCase().trim();
        list = list.filter((v) => {
            const nameMatch = v.name?.toLowerCase().includes(query);
            const brandMatch = v.brand?.toLowerCase().includes(query);
            const catMatch = v.category_label?.toLowerCase().includes(query);
            const subMatch = v.subtitle?.toLowerCase().includes(query);
            const transMatch = v.transmission_label?.toLowerCase().includes(query);
            return nameMatch || brandMatch || catMatch || subMatch || transMatch;
        });
    }

    // 6. Sort logic (Reactive Price Period Sort)
    if (sortBy.value === 'price-asc') {
        list.sort((a, b) => {
            const priceA = a.prices?.[pricePeriod.value] || a.price_daily || 0;
            const priceB = b.prices?.[pricePeriod.value] || b.price_daily || 0;
            return priceA - priceB;
        });
    } else if (sortBy.value === 'price-desc') {
        list.sort((a, b) => {
            const priceA = a.prices?.[pricePeriod.value] || a.price_daily || 0;
            const priceB = b.prices?.[pricePeriod.value] || b.price_daily || 0;
            return priceB - priceA;
        });
    } else if (sortBy.value === 'year') {
        list.sort((a, b) => parseInt(b.year || 0) - parseInt(a.year || 0));
    }

    return list;
});

// Check if any filter is active
const isFilterActive = computed(() => {
    return (
        searchQuery.value.trim() !== '' ||
        vehicleTypeFilter.value !== 'all' ||
        categoryFilter.value !== 'all' ||
        transmissionFilter.value !== 'all' ||
        onlyAvailable.value === true ||
        sortBy.value !== 'popular' ||
        pricePeriod.value !== 'daily'
    );
});

// Helper count functions
const getVehicleTypeCount = (type) => {
    if (type === 'all') return props.vehicles.length;
    return props.vehicles.filter((v) => v.type === type).length;
};

// Reset all filters to default
const resetFilters = () => {
    searchQuery.value = '';
    vehicleTypeFilter.value = 'all';
    categoryFilter.value = 'all';
    transmissionFilter.value = 'all';
    onlyAvailable.value = false;
    sortBy.value = 'popular';
    pricePeriod.value = 'daily';
};

// Detail Modal Triggers
const openDetailModal = (vehicle, price, period) => {
    selectedDetailVehicle.value = vehicle;
    selectedDetailPrice.value = price;
    selectedDetailPeriod.value = period;
};

const closeDetailModal = () => {
    selectedDetailVehicle.value = null;
};

const formatRupiah = (val) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(val || 0);
};

const getPeriodSuffix = (period) => {
    switch (period) {
        case 'weekly':
            return '/ minggu';
        case 'monthly':
            return '/ bulan';
        default:
            return '/ hari';
    }
};

const getWhatsAppModalUrl = () => {
    if (!selectedDetailVehicle.value) return '#';
    const periodName = selectedDetailPeriod.value === 'weekly' ? 'Mingguan' : selectedDetailPeriod.value === 'monthly' ? 'Bulanan' : 'Harian';
    const priceFormatted = formatRupiah(selectedDetailPrice.value);
    const suffix = getPeriodSuffix(selectedDetailPeriod.value);
    const availabilityNote = selectedDetailVehicle.value.status === 'available' || selectedDetailVehicle.value.is_available ? 'Tersedia' : 'Cek Jadwal Berikutnya';

    const text = encodeURIComponent(
        `Halo Admin X-RENT, saya ingin sewa unit:\n*${selectedDetailVehicle.value.name}*\nPaket: *${periodName}* (${priceFormatted}${suffix})\nTransmisi: *${selectedDetailVehicle.value.transmission_label || 'Matic'}*\nStatus: *${availabilityNote}*\nOpsi: *${selectedDetailVehicle.value.driver_option || 'Lepas Kunci'}*\nMohon info ketersediaan dan verifikasi berkas.`
    );
    return `https://wa.me/6281288889999?text=${text}`;
};
</script>

<template>
    <Head title="Katalog Armada & Tarif Sewa - X-RENT Jakarta" />

    <MainLayout>
        <div class="flex flex-col w-full bg-surface">
            <!-- BREADCRUMB & HEADER SECTION (Stitch Material 3 Reference) -->
            <section class="w-full bg-surface-container-low border-b border-surface-container-highest/60 pt-6 pb-10">
                <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Breadcrumbs -->
                    <nav class="flex items-center gap-2 text-xs font-spec-data text-on-surface-variant mb-4" aria-label="Breadcrumb">
                        <Link href="/" class="hover:text-primary transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">home</span>
                            <span>Beranda</span>
                        </Link>
                        <span class="text-outline-variant">/</span>
                        <span class="text-primary font-bold">Katalog Armada & Tarif Sewa</span>
                    </nav>

                    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                        <div class="max-w-2xl">
                            <div class="inline-flex items-center gap-2 rounded-full bg-surface-container-lowest px-3.5 py-1.5 shadow-xs border border-surface-container-highest/60 mb-3">
                                <span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                                <span class="font-label-badge text-[11px] uppercase tracking-wider text-primary font-bold">
                                    Katalog Resmi X-RENT 2026
                                </span>
                            </div>

                            <h1 class="font-display-hero text-2xl sm:text-3xl lg:text-4xl font-extrabold text-on-surface tracking-tight leading-tight">
                                Katalog Armada Motor & Mobil <span class="text-primary">Tarif Transparan</span>
                            </h1>

                            <p class="mt-2.5 font-body-md text-sm sm:text-base text-on-surface-variant leading-relaxed">
                                Temukan motor matic lincah perkotaan dan mobil keluarga nyaman terawat. Seluruh unit siap pakai dengan jaminan inspeksi mekanis 40 titik, asuransi All-Risk, dan verifikasi cepat 15 menit via WhatsApp.
                            </p>
                        </div>

                        <!-- Trust Metric Pills -->
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="flex items-center gap-2.5 rounded-2xl bg-surface-container-lowest px-4 py-3 shadow-xs border border-surface-container-highest/70">
                                <span class="material-symbols-outlined text-primary text-[22px]">directions_car</span>
                                <div>
                                    <span class="block font-headline-sm text-sm font-bold text-on-surface leading-none">{{ vehicles.length }}+ Unit</span>
                                    <span class="font-spec-data text-[11px] text-on-surface-variant">Motor & Mobil Ready</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 rounded-2xl bg-surface-container-lowest px-4 py-3 shadow-xs border border-surface-container-highest/70">
                                <span class="material-symbols-outlined text-tertiary text-[22px]">verified_user</span>
                                <div>
                                    <span class="block font-headline-sm text-sm font-bold text-on-surface leading-none">All-Risk</span>
                                    <span class="font-spec-data text-[11px] text-on-surface-variant">Proteksi Asuransi</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 rounded-2xl bg-surface-container-lowest px-4 py-3 shadow-xs border border-surface-container-highest/70">
                                <span class="material-symbols-outlined text-tertiary-container text-[22px]">bolt</span>
                                <div>
                                    <span class="block font-headline-sm text-sm font-bold text-on-surface leading-none">15 Menit</span>
                                    <span class="font-spec-data text-[11px] text-on-surface-variant">Verifikasi WhatsApp</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- INTERACTIVE CONTROL & FILTER TOOLBAR (Sticky Filter Bar) -->
            <section class="sticky top-20 z-40 w-full bg-surface-container-lowest/95 backdrop-blur-md border-b border-surface-container-highest/80 shadow-xs py-4 transition-all">
                <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
                    <!-- Row 1: Search Input + Jenis Kendaraan Pills (Semua, Motor, Mobil) -->
                    <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                        <!-- Search Bar -->
                        <div class="relative w-full lg:w-96">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px] pointer-events-none">
                                search
                            </span>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Cari nama kendaraan (Beat, NMAX, Avanza, Brio)..."
                                class="w-full rounded-xl bg-surface-container-low pl-10 pr-10 py-2.5 text-xs sm:text-sm text-on-surface placeholder:text-on-surface-variant/70 border border-surface-container-highest focus:border-primary focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                            />
                            <button
                                v-if="searchQuery"
                                type="button"
                                @click="searchQuery = ''"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface p-1"
                                title="Hapus pencarian"
                            >
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </button>
                        </div>

                        <!-- Type Filters: Semua / Motor / Mobil (Task 2.3 requirement) -->
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 lg:pb-0 scrollbar-none">
                            <button
                                type="button"
                                @click="vehicleTypeFilter = 'all'"
                                class="shrink-0 rounded-xl px-4 py-2 font-title-md text-xs font-semibold transition-all flex items-center gap-1.5"
                                :class="[
                                    vehicleTypeFilter === 'all'
                                        ? 'bg-primary text-on-primary shadow-xs font-bold'
                                        : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-variant hover:text-on-surface'
                                ]"
                            >
                                <span class="material-symbols-outlined text-[16px]">apps</span>
                                <span>Semua Unit</span>
                                <span class="rounded-full px-1.5 py-0.2 text-[10px]" :class="vehicleTypeFilter === 'all' ? 'bg-on-primary/20 text-on-primary' : 'bg-surface-container'">
                                    {{ getVehicleTypeCount('all') }}
                                </span>
                            </button>

                            <button
                                type="button"
                                @click="vehicleTypeFilter = 'motor'"
                                class="shrink-0 rounded-xl px-4 py-2 font-title-md text-xs font-semibold transition-all flex items-center gap-1.5"
                                :class="[
                                    vehicleTypeFilter === 'motor'
                                        ? 'bg-primary text-on-primary shadow-xs font-bold'
                                        : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-variant hover:text-on-surface'
                                ]"
                            >
                                <span class="material-symbols-outlined text-[16px]">two_wheeler</span>
                                <span>Armada Motor</span>
                                <span class="rounded-full px-1.5 py-0.2 text-[10px]" :class="vehicleTypeFilter === 'motor' ? 'bg-on-primary/20 text-on-primary' : 'bg-surface-container'">
                                    {{ getVehicleTypeCount('motor') }}
                                </span>
                            </button>

                            <button
                                type="button"
                                @click="vehicleTypeFilter = 'mobil'"
                                class="shrink-0 rounded-xl px-4 py-2 font-title-md text-xs font-semibold transition-all flex items-center gap-1.5"
                                :class="[
                                    vehicleTypeFilter === 'mobil'
                                        ? 'bg-primary text-on-primary shadow-xs font-bold'
                                        : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-variant hover:text-on-surface'
                                ]"
                            >
                                <span class="material-symbols-outlined text-[16px]">directions_car</span>
                                <span>Armada Mobil</span>
                                <span class="rounded-full px-1.5 py-0.2 text-[10px]" :class="vehicleTypeFilter === 'mobil' ? 'bg-on-primary/20 text-on-primary' : 'bg-surface-container'">
                                    {{ getVehicleTypeCount('mobil') }}
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Row 2: Sub-category pills + Transmisi + Availability Toggle + Sorting + Pricing Period -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-surface-container-high/60 text-xs">
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Transmisi Filter (Semua / Matic / Manual) -->
                            <div class="flex items-center gap-1.5 bg-surface-container-low px-2 py-1 rounded-xl border border-surface-container-highest/80">
                                <span class="font-spec-data text-on-surface-variant font-medium">Transmisi:</span>
                                <select
                                    v-model="transmissionFilter"
                                    class="bg-transparent font-spec-data text-xs text-on-surface font-semibold focus:outline-none cursor-pointer"
                                >
                                    <option value="all">Semua Transmisi</option>
                                    <option value="matic">Matic / CVT</option>
                                    <option value="manual">Manual</option>
                                </select>
                            </div>

                            <!-- Global Pricing Period Switcher (Harian / Mingguan / Bulanan) -->
                            <div class="flex items-center gap-1 bg-surface-container p-1 rounded-xl font-spec-data font-semibold">
                                <button
                                    type="button"
                                    @click="pricePeriod = 'daily'"
                                    class="rounded-lg px-2.5 py-1 transition-all"
                                    :class="[
                                        pricePeriod === 'daily'
                                            ? 'bg-surface-container-lowest text-primary font-bold shadow-xs'
                                            : 'text-on-surface-variant hover:text-on-surface'
                                    ]"
                                >
                                    Harian
                                </button>
                                <button
                                    type="button"
                                    @click="pricePeriod = 'weekly'"
                                    class="rounded-lg px-2.5 py-1 transition-all flex items-center gap-1"
                                    :class="[
                                        pricePeriod === 'weekly'
                                            ? 'bg-surface-container-lowest text-primary font-bold shadow-xs'
                                            : 'text-on-surface-variant hover:text-on-surface'
                                    ]"
                                >
                                    <span>Mingguan</span>
                                    <span class="rounded bg-tertiary-fixed text-tertiary text-[9px] font-bold px-1">Hemat 10%</span>
                                </button>
                                <button
                                    type="button"
                                    @click="pricePeriod = 'monthly'"
                                    class="rounded-lg px-2.5 py-1 transition-all flex items-center gap-1"
                                    :class="[
                                        pricePeriod === 'monthly'
                                            ? 'bg-surface-container-lowest text-primary font-bold shadow-xs'
                                            : 'text-on-surface-variant hover:text-on-surface'
                                    ]"
                                >
                                    <span>Bulanan</span>
                                    <span class="rounded bg-tertiary-fixed text-tertiary text-[9px] font-bold px-1">Hemat 25%</span>
                                </button>
                            </div>

                            <!-- Availability Toggle (Hanya Tersedia) -->
                            <label class="flex items-center gap-2 cursor-pointer select-none py-1 px-2.5 rounded-lg hover:bg-surface-container-low transition">
                                <input
                                    v-model="onlyAvailable"
                                    type="checkbox"
                                    class="w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant cursor-pointer"
                                />
                                <span class="font-spec-data text-on-surface font-semibold flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
                                    Hanya Unit Tersedia
                                </span>
                            </label>
                        </div>

                        <!-- Sort & Reset Filters -->
                        <div class="flex items-center gap-3 ml-auto">
                            <!-- Sort Selector (Termurah ke Tertinggi & sebaliknya) -->
                            <div class="flex items-center gap-1.5">
                                <span class="font-spec-data text-on-surface-variant font-medium hidden md:inline">
                                    Urutkan:
                                </span>
                                <select
                                    v-model="sortBy"
                                    class="rounded-xl bg-surface-container-low border border-surface-container-highest px-3 py-1.5 font-spec-data text-xs text-on-surface font-semibold focus:border-primary focus:outline-none transition cursor-pointer"
                                >
                                    <option value="popular">Paling Populer</option>
                                    <option value="price-asc">Tarif: Termurah ke Tertinggi</option>
                                    <option value="price-desc">Tarif: Tertinggi ke Termurah</option>
                                    <option value="year">Tahun: Terbaru</option>
                                </select>
                            </div>

                            <!-- Reset Button -->
                            <button
                                v-if="isFilterActive"
                                type="button"
                                @click="resetFilters"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-surface-container-high text-on-surface-variant hover:text-primary hover:bg-surface-variant transition font-spec-data text-xs font-semibold"
                                title="Reset semua filter"
                            >
                                <span class="material-symbols-outlined text-[15px]">restart_alt</span>
                                <span>Reset</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CATALOG VEHICLE GRID CONTENT -->
            <section class="w-full py-10 lg:py-16">
                <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Status & Count Bar -->
                    <div class="flex items-center justify-between mb-8 pb-3 border-b border-surface-container-highest/60">
                        <div class="flex items-center gap-2">
                            <span class="font-title-md text-sm font-bold text-on-surface">
                                Menampilkan
                                <span class="text-primary">{{ filteredVehicles.length }}</span>
                                dari {{ vehicles.length }} unit armada
                            </span>
                            <span
                                v-if="vehicleTypeFilter !== 'all'"
                                class="rounded-full bg-primary-fixed text-primary px-2.5 py-0.5 text-[11px] font-bold uppercase"
                            >
                                {{ vehicleTypeFilter }}
                            </span>
                            <span
                                v-if="transmissionFilter !== 'all'"
                                class="rounded-full bg-surface-container-high text-on-surface-variant px-2.5 py-0.5 text-[11px] font-semibold uppercase"
                            >
                                {{ transmissionFilter }}
                            </span>
                        </div>

                        <!-- Pricing Period Indicator -->
                        <span class="font-spec-data text-xs text-on-surface-variant">
                            Tarif Aktif: <strong class="text-on-surface capitalize">{{ pricePeriod === 'weekly' ? 'Mingguan (Diskon 10%)' : pricePeriod === 'monthly' ? 'Bulanan (Diskon 25%)' : 'Harian' }}</strong>
                        </span>
                    </div>

                    <!-- Grid List using VehicleCard -->
                    <div
                        v-if="filteredVehicles.length > 0"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
                    >
                        <VehicleCard
                            v-for="vehicle in filteredVehicles"
                            :key="vehicle.id"
                            :vehicle="vehicle"
                            :period="pricePeriod"
                            @open-detail="openDetailModal"
                        />
                    </div>

                    <!-- EMPTY STATE IF NO VEHICLE FOUND -->
                    <div
                        v-else
                        class="rounded-3xl bg-surface-container-lowest p-12 text-center max-w-lg mx-auto border border-surface-container-highest/80 shadow-xs"
                    >
                        <div class="w-16 h-16 rounded-2xl bg-surface-container-high text-on-surface-variant flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-outlined text-[36px]">search_off</span>
                        </div>
                        <h3 class="font-headline-sm text-lg font-bold text-on-surface">
                            Tidak Ada Kendaraan yang Cocok
                        </h3>
                        <p class="font-body-md text-xs sm:text-sm text-on-surface-variant mt-2 leading-relaxed">
                            Kami tidak menemukan unit yang sesuai dengan kombinasi filter Anda{{ searchQuery ? ` untuk "${searchQuery}"` : '' }}. Cobalah ganti transmisi atau nonaktifkan centang "Hanya Unit Tersedia".
                        </p>
                        <div class="mt-6 flex justify-center gap-3">
                            <button
                                type="button"
                                @click="resetFilters"
                                class="inline-flex items-center gap-2 rounded-xl bg-primary text-on-primary px-5 py-2.5 font-title-md text-xs font-semibold shadow-xs hover:bg-primary-container transition"
                            >
                                <span class="material-symbols-outlined text-[16px]">refresh</span>
                                <span>Reset Semua Filter</span>
                            </button>
                        </div>
                    </div>

                    <!-- CORPORATE & CUSTOM BOOKING BANNER CALLOUT -->
                    <div class="mt-16 rounded-3xl bg-gradient-to-r from-primary to-primary-container p-8 text-on-primary shadow-xl">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                            <div class="space-y-2 max-w-2xl">
                                <span class="inline-block rounded-full bg-on-primary/20 px-3 py-1 font-label-badge text-[11px] uppercase tracking-wider text-on-primary font-bold">
                                    Rental Perusahaan & Rombongan
                                </span>
                                <h3 class="font-headline-md text-xl sm:text-2xl font-bold text-on-primary">
                                    Butuh Sewa Mobil/Motor Kontrak Bulanan atau Armada Wisata?
                                </h3>
                                <p class="font-body-md text-sm text-on-primary-container leading-relaxed">
                                    Dapatkan penawaran tarif korporat khusus dengan fasilitas penggantian unit cepat jika ada kendala teknis, faktur pajak resmi, dan pilihan armada seragam.
                                </p>
                            </div>

                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0">
                                <a
                                    href="https://wa.me/6281288889999?text=Halo%20X-RENT,%20saya%20ingin%20konsultasi%20rental%20kendaraan%20kontrak%20bulanan/rombongan%20perusahaan"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-surface-container-lowest px-6 py-3.5 font-title-md text-xs sm:text-sm font-bold text-primary shadow-md hover:bg-surface-container-low transition active:scale-95 text-center"
                                >
                                    <span class="material-symbols-outlined text-[18px]">support_agent</span>
                                    <span>Konsultasi Khusus via WA</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- MODAL DETAIL UNIT KENDARAAN (Stitch Material 3 Reference) -->
        <div
            v-if="selectedDetailVehicle"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            @click.self="closeDetailModal"
        >
            <div
                class="relative w-full max-w-lg rounded-3xl bg-surface-container-lowest p-6 shadow-2xl border border-surface-container-highest max-h-[90vh] overflow-y-auto"
            >
                <!-- Close Button -->
                <button
                    type="button"
                    @click="closeDetailModal"
                    class="absolute top-4 right-4 p-2 rounded-full hover:bg-surface-container-low text-on-surface-variant transition focus:outline-none"
                    aria-label="Tutup Detail"
                >
                    ✕
                </button>

                <!-- Vehicle Modal Image & Header -->
                <div class="relative h-52 rounded-2xl overflow-hidden bg-surface-container-low mb-4">
                    <img
                        :src="selectedDetailVehicle.image"
                        :alt="selectedDetailVehicle.name"
                        class="w-full h-full object-cover"
                    />
                    <span class="absolute top-3 left-3 rounded-full bg-primary px-3 py-1 font-label-badge text-[11px] text-on-primary font-bold shadow-sm">
                        {{ selectedDetailVehicle.category_label || selectedDetailVehicle.category }}
                    </span>
                    <span
                        class="absolute top-3 right-12 rounded-full px-2.5 py-1 text-[11px] font-bold backdrop-blur-md shadow-xs"
                        :class="[
                            selectedDetailVehicle.status === 'available' || selectedDetailVehicle.is_available
                                ? 'bg-surface-container-lowest/95 text-tertiary border border-tertiary/20'
                                : selectedDetailVehicle.status === 'maintenance'
                                ? 'bg-surface-container-lowest/95 text-rose-700 border border-rose-200'
                                : 'bg-surface-container-highest/95 text-on-surface-variant'
                        ]"
                    >
                        {{ selectedDetailVehicle.status_label || (selectedDetailVehicle.is_available ? 'Tersedia' : 'Sedang Disewa') }}
                    </span>
                </div>

                <!-- Title & Price -->
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="font-headline-sm text-xl font-bold text-on-surface">
                            {{ selectedDetailVehicle.name }}
                        </h3>
                        <p class="font-body-sm text-xs text-on-surface-variant mt-0.5">
                            Tahun {{ selectedDetailVehicle.year }} • Transmisi: {{ selectedDetailVehicle.transmission_label || 'Matic' }} • Opsi: {{ selectedDetailVehicle.driver_option }}
                        </p>
                    </div>

                    <div class="text-right shrink-0">
                        <span class="block font-label-badge text-[10px] text-on-surface-variant uppercase">
                            Tarif {{ selectedDetailPeriod === 'weekly' ? 'Mingguan' : selectedDetailPeriod === 'monthly' ? 'Bulanan' : 'Harian' }}
                        </span>
                        <div class="font-headline-sm text-lg font-bold text-primary">
                            {{ formatRupiah(selectedDetailPrice) }}
                            <span class="text-xs font-normal text-on-surface-variant">{{ getPeriodSuffix(selectedDetailPeriod) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Subtitle / Highlight notes -->
                <p v-if="selectedDetailVehicle.subtitle" class="mt-3 text-xs bg-surface-container-low p-3 rounded-xl text-on-surface font-medium leading-relaxed">
                    ✨ {{ selectedDetailVehicle.subtitle }}
                </p>

                <!-- Spec Grid Matrix -->
                <div class="mt-4 pt-3 border-t border-surface-container-highest">
                    <p class="font-title-md text-xs font-bold text-on-surface mb-2">Spesifikasi Unit:</p>
                    <div class="grid grid-cols-2 gap-2">
                        <div
                            v-for="(spec, sIdx) in selectedDetailVehicle.specs"
                            :key="sIdx"
                            class="flex items-center gap-2 rounded-lg bg-surface-container-low px-2.5 py-2 text-on-surface text-xs"
                        >
                            <span class="material-symbols-outlined text-[18px] text-primary shrink-0">
                                {{ spec.icon }}
                            </span>
                            <span class="font-spec-data text-[12px] truncate">{{ spec.label }}</span>
                        </div>
                    </div>
                </div>

                <!-- Features list -->
                <div v-if="selectedDetailVehicle.features" class="mt-4 pt-3 border-t border-surface-container-highest">
                    <p class="font-title-md text-xs font-bold text-on-surface mb-2">Fitur & Fasilitas Termasuk:</p>
                    <ul class="grid grid-cols-2 gap-2 text-xs text-on-surface-variant">
                        <li
                            v-for="(feat, fIdx) in selectedDetailVehicle.features"
                            :key="fIdx"
                            class="flex items-center gap-1.5"
                        >
                            <span class="material-symbols-outlined text-[16px] text-tertiary shrink-0">check</span>
                            <span class="truncate">{{ feat }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Facilities description -->
                <p v-if="selectedDetailVehicle.facilities" class="mt-3 text-[11px] text-on-surface-variant leading-relaxed">
                    <strong>Keterangan:</strong> {{ selectedDetailVehicle.facilities }}
                </p>

                <!-- Modal Action CTAs -->
                <div class="mt-6 flex items-center gap-3 pt-3 border-t border-surface-container-highest">
                    <button
                        type="button"
                        @click="closeDetailModal"
                        class="flex-1 py-3 rounded-xl bg-surface-container-high text-xs font-bold text-on-surface hover:bg-surface-variant transition active:scale-95"
                    >
                        Tutup
                    </button>
                    <a
                        :href="getWhatsAppModalUrl()"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex-1 py-3 rounded-xl bg-primary text-xs font-bold text-on-primary text-center hover:bg-primary-container shadow-md transition active:scale-95 flex items-center justify-center gap-1.5"
                    >
                        <span class="material-symbols-outlined text-[16px]">chat</span>
                        <span>Sewa via WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
