<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import VehicleCard from '@/Components/VehicleCard.vue';

const props = defineProps({
    cars: {
        type: Array,
        default: () => [],
    },
    motors: {
        type: Array,
        default: () => [],
    },
    faqs: {
        type: Array,
        default: () => [],
    },
    hotline: {
        type: String,
        default: '0812-8888-9999',
    },
    garageLocations: {
        type: Array,
        default: () => [],
    },
});

// Hero Booking Form State
const bookingVehicleType = ref('mobil'); // 'mobil' | 'motor'
const serviceType = ref('lepas_kunci'); // 'lepas_kunci' | 'dengan_supir'
const selectedLocation = ref('cilandak');
const startDate = ref('2026-10-15');
const startTime = ref('08:00 WIB');
const endDate = ref('2026-10-17');
const endTime = ref('08:00 WIB');

// Car Filter State
const carFilter = ref('all'); // all, city, mpv, suv

// Motor Filter State
const motorFilter = ref('all'); // all, maxi, retro, matic

// FAQ Open Index
const openFaqIndex = ref(0);

// Modal Detail State
const selectedDetailVehicle = ref(null);
const selectedDetailPrice = ref(0);
const selectedDetailPeriod = ref('daily');

const filteredCars = computed(() => {
    if (carFilter.value === 'all') return props.cars;
    return props.cars.filter((c) => c.category === carFilter.value);
});

const filteredMotors = computed(() => {
    if (motorFilter.value === 'all') return props.motors;
    return props.motors.filter((m) => m.category === motorFilter.value);
});

const handleSearchVehicles = (e) => {
    e.preventDefault();
    if (bookingVehicleType.value === 'motor') {
        const el = document.getElementById('armada-motor');
        if (el) el.scrollIntoView({ behavior: 'smooth' });
    } else {
        const el = document.getElementById('armada-mobil');
        if (el) el.scrollIntoView({ behavior: 'smooth' });
    }
};

const openDetailModal = (vehicle, price, period) => {
    selectedDetailVehicle.value = vehicle;
    selectedDetailPrice.value = price;
    selectedDetailPeriod.value = period;
};

const closeDetailModal = () => {
    selectedDetailVehicle.value = null;
};

const toggleFaq = (index) => {
    openFaqIndex.value = openFaqIndex.value === index ? null : index;
};

const formatRupiah = (val) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(val);
};

const getHeroWhatsAppSearchUrl = () => {
    const vType = bookingVehicleType.value === 'motor' ? 'Motor' : 'Mobil';
    const sType = serviceType.value === 'dengan_supir' ? 'Dengan Supir' : 'Lepas Kunci';
    const text = encodeURIComponent(
        `Halo Admin X-RENT, saya ingin cek unit *${vType}* (${sType}) untuk jadwal:\nLokasi: *${selectedLocation.value}*\nMulai: *${startDate.value} (${startTime.value})*\nSelesai: *${endDate.value} (${endTime.value})*\nApakah ada unit yang tersedia?`
    );
    return `https://wa.me/6281288889999?text=${text}`;
};
</script>

<template>
    <Head title="X-RENT - Rental Mobil & Motor Terpercaya Jakarta" />

    <MainLayout>
        <div class="flex flex-col w-full">
            <!-- HERO BOOKING SECTION (Stitch Reference) -->
            <section class="relative w-full overflow-hidden bg-surface-container-low pb-16 pt-8 lg:pb-24 lg:pt-14">
                <!-- Atmospheric Ambient Accents -->
                <div class="pointer-events-none absolute -top-40 right-[-10%] h-[550px] w-[550px] rounded-full bg-primary/10 blur-[130px]"></div>
                <div class="pointer-events-none absolute -bottom-20 left-[-8%] h-[400px] w-[400px] rounded-full bg-tertiary-container/10 blur-[110px]"></div>

                <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-12">
                        <!-- Left: Editorial Typography & Value Matrix -->
                        <div class="flex flex-col items-start lg:col-span-6">
                            <div class="inline-flex items-center gap-2 rounded-full bg-surface-container-lowest px-3.5 py-1.5 shadow-sm border border-surface-container-highest/60">
                                <span class="flex h-2 w-2 rounded-full bg-tertiary animate-pulse"></span>
                                <span class="font-label-badge text-[11px] uppercase tracking-wider text-tertiary-container font-bold">
                                    Sistem Booking Otomatis 24 Jam
                                </span>
                            </div>

                            <h1 class="mt-4 font-display-hero text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-on-surface leading-[1.18]">
                                Sewa Mobil & Motor <span class="text-primary">Mudah, Cepat</span> & Terpercaya
                            </h1>

                            <p class="mt-4 font-body-lg text-base lg:text-lg text-on-surface-variant max-w-xl leading-relaxed">
                                Pilihan unit terlengkap untuk perjalanan bisnis, liburan keluarga, maupun mobilitas harian. Tersedia opsi Lepas Kunci 24 Jam atau lengkap dengan supir profesional ramah rute.
                            </p>

                            <!-- Core Value Badges Pill Grid -->
                            <div class="mt-8 grid grid-cols-2 gap-3 sm:flex sm:flex-wrap">
                                <div class="flex items-center gap-2 rounded-xl bg-surface-container-lowest px-3.5 py-2.5 shadow-sm border border-surface-container-highest/60">
                                    <span class="material-symbols-outlined text-[18px] text-tertiary">check_circle</span>
                                    <span class="font-spec-data text-xs text-on-surface font-semibold">Unit Prima & Bersih</span>
                                </div>
                                <div class="flex items-center gap-2 rounded-xl bg-surface-container-lowest px-3.5 py-2.5 shadow-sm border border-surface-container-highest/60">
                                    <span class="material-symbols-outlined text-[18px] text-primary">airport_shuttle</span>
                                    <span class="font-spec-data text-xs text-on-surface font-semibold">Antar-Jemput Bandara</span>
                                </div>
                                <div class="flex items-center gap-2 rounded-xl bg-surface-container-lowest px-3.5 py-2.5 shadow-sm border border-surface-container-highest/60">
                                    <span class="material-symbols-outlined text-[18px] text-tertiary">bolt</span>
                                    <span class="font-spec-data text-xs text-on-surface font-semibold">Verifikasi 15 Menit</span>
                                </div>
                                <div class="flex items-center gap-2 rounded-xl bg-surface-container-lowest px-3.5 py-2.5 shadow-sm border border-surface-container-highest/60">
                                    <span class="material-symbols-outlined text-[18px] text-primary">shield</span>
                                    <span class="font-spec-data text-xs text-on-surface font-semibold">Asuransi All-Risk</span>
                                </div>
                            </div>

                            <!-- Quick Metrics Strip -->
                            <div class="mt-10 flex items-center gap-8 border-t-0 pt-2">
                                <div>
                                    <div class="font-headline-md text-2xl font-bold text-on-surface">1,250+</div>
                                    <div class="font-body-sm text-xs text-on-surface-variant mt-0.5">Unit Terawat</div>
                                </div>
                                <div class="h-8 w-px bg-surface-variant"></div>
                                <div>
                                    <div class="font-headline-md text-2xl font-bold text-on-surface">4.9 / 5.0</div>
                                    <div class="font-body-sm text-xs text-on-surface-variant mt-0.5">Rating Google (4.8k+)</div>
                                </div>
                                <div class="h-8 w-px bg-surface-variant"></div>
                                <div>
                                    <div class="font-headline-md text-2xl font-bold text-primary">0%</div>
                                    <div class="font-body-sm text-xs text-on-surface-variant mt-0.5">Biaya Tersembunyi</div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Interactive Quick Booking Search Card -->
                        <div class="lg:col-span-6">
                            <div class="relative rounded-2xl bg-surface-container-lowest p-6 shadow-xl lg:p-8 border border-surface-container-highest/60">
                                <!-- Category Segment Switch -->
                                <div class="flex rounded-xl bg-surface-container p-1.5" id="vehicle-type-container">
                                    <button
                                        type="button"
                                        @click="bookingVehicleType = 'mobil'"
                                        class="flex flex-1 items-center justify-center gap-2 rounded-lg py-2.5 font-title-md text-sm font-semibold transition-all"
                                        :class="[
                                            bookingVehicleType === 'mobil'
                                                ? 'bg-surface-container-lowest shadow-sm text-primary'
                                                : 'text-on-surface-variant hover:text-on-surface'
                                        ]"
                                    >
                                        <span class="material-symbols-outlined text-[20px]">directions_car</span>
                                        <span>Rental Mobil</span>
                                    </button>
                                    <button
                                        type="button"
                                        @click="bookingVehicleType = 'motor'"
                                        class="flex flex-1 items-center justify-center gap-2 rounded-lg py-2.5 font-title-md text-sm font-semibold transition-all"
                                        :class="[
                                            bookingVehicleType === 'motor'
                                                ? 'bg-surface-container-lowest shadow-sm text-primary'
                                                : 'text-on-surface-variant hover:text-on-surface'
                                        ]"
                                    >
                                        <span class="material-symbols-outlined text-[20px]">two_wheeler</span>
                                        <span>Rental Motor</span>
                                    </button>
                                </div>

                                <!-- Booking Form Elements -->
                                <form class="mt-6 space-y-4" @submit="handleSearchVehicles">
                                    <!-- Layanan Mode Selector -->
                                    <div class="rounded-xl bg-surface-container-low p-3.5 border border-surface-container-highest/60">
                                        <span class="block font-label-badge text-[11px] uppercase text-on-surface-variant mb-2 font-bold tracking-wider">
                                            Pilihan Tipe Layanan
                                        </span>
                                        <div class="grid grid-cols-2 gap-2">
                                            <label class="flex cursor-pointer items-center gap-2.5 rounded-lg bg-surface-container-lowest px-3 py-2 text-on-surface shadow-xs transition hover:bg-surface-bright border border-surface-container-highest/40">
                                                <input
                                                    v-model="serviceType"
                                                    type="radio"
                                                    value="lepas_kunci"
                                                    class="h-4 w-4 text-primary focus:ring-0"
                                                />
                                                <span class="font-body-sm text-xs font-semibold">Lepas Kunci (24 Jam)</span>
                                            </label>
                                            <label class="flex cursor-pointer items-center gap-2.5 rounded-lg bg-surface-container-lowest px-3 py-2 text-on-surface shadow-xs transition hover:bg-surface-bright border border-surface-container-highest/40">
                                                <input
                                                    v-model="serviceType"
                                                    type="radio"
                                                    value="dengan_supir"
                                                    class="h-4 w-4 text-primary focus:ring-0"
                                                />
                                                <span class="font-body-sm text-xs font-semibold">Dengan Supir</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Pickup Location Dropdown -->
                                    <div>
                                        <label class="mb-1.5 block font-title-md text-xs font-semibold text-on-surface">
                                            Lokasi Pengambilan & Pengembalian
                                        </label>
                                        <div class="relative">
                                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-primary text-[20px]">pin_drop</span>
                                            <select
                                                v-model="selectedLocation"
                                                class="w-full appearance-none rounded-xl bg-surface-container-low py-3 pl-11 pr-10 font-body-md text-xs sm:text-sm text-on-surface focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary border border-surface-container-highest/60"
                                            >
                                                <option
                                                    v-for="loc in garageLocations"
                                                    :key="loc.id"
                                                    :value="loc.name"
                                                >
                                                    {{ loc.name }}
                                                </option>
                                            </select>
                                            <span class="material-symbols-outlined absolute right-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[20px]">expand_more</span>
                                        </div>
                                    </div>

                                    <!-- Date & Time Pickers Grid -->
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                        <!-- Mulai Sewa -->
                                        <div>
                                            <label class="mb-1.5 block font-title-md text-xs font-semibold text-on-surface">Mulai Sewa</label>
                                            <div class="space-y-2">
                                                <div class="relative">
                                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[18px]">calendar_today</span>
                                                    <input
                                                        v-model="startDate"
                                                        type="date"
                                                        class="w-full rounded-xl bg-surface-container-low py-2.5 pl-9 pr-3 font-spec-data text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary border border-surface-container-highest/60"
                                                    />
                                                </div>
                                                <div class="relative">
                                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[18px]">schedule</span>
                                                    <select
                                                        v-model="startTime"
                                                        class="w-full appearance-none rounded-xl bg-surface-container-low py-2 pl-9 pr-8 font-spec-data text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary border border-surface-container-highest/60"
                                                    >
                                                        <option>08:00 WIB</option>
                                                        <option>10:00 WIB</option>
                                                        <option>13:00 WIB</option>
                                                        <option>17:00 WIB</option>
                                                        <option>20:00 WIB</option>
                                                    </select>
                                                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[16px]">expand_more</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Selesai Sewa -->
                                        <div>
                                            <label class="mb-1.5 block font-title-md text-xs font-semibold text-on-surface">Selesai Sewa</label>
                                            <div class="space-y-2">
                                                <div class="relative">
                                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-tertiary-container text-[18px]">event_repeat</span>
                                                    <input
                                                        v-model="endDate"
                                                        type="date"
                                                        class="w-full rounded-xl bg-surface-container-low py-2.5 pl-9 pr-3 font-spec-data text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary border border-surface-container-highest/60"
                                                    />
                                                </div>
                                                <div class="relative">
                                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-tertiary-container text-[18px]">schedule</span>
                                                    <select
                                                        v-model="endTime"
                                                        class="w-full appearance-none rounded-xl bg-surface-container-low py-2 pl-9 pr-8 font-spec-data text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary border border-surface-container-highest/60"
                                                    >
                                                        <option>08:00 WIB (Durasi 2 Hari)</option>
                                                        <option>10:00 WIB</option>
                                                        <option>13:00 WIB</option>
                                                        <option>17:00 WIB</option>
                                                        <option>20:00 WIB</option>
                                                    </select>
                                                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[16px]">expand_more</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Call to Action Buttons -->
                                    <div class="pt-2 flex flex-col sm:flex-row gap-2.5">
                                        <button
                                            type="submit"
                                            class="flex-1 flex items-center justify-center gap-2 rounded-xl bg-primary py-3 px-6 font-title-md text-sm font-semibold text-on-primary shadow-md transition hover:bg-primary-container active:scale-[0.99]"
                                        >
                                            <span class="material-symbols-outlined text-[20px]">search</span>
                                            <span>Cari {{ bookingVehicleType === 'motor' ? 'Motor' : 'Mobil' }} Tersedia</span>
                                        </button>
                                        <a
                                            :href="getHeroWhatsAppSearchUrl()"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="flex items-center justify-center gap-1.5 px-4 py-3 rounded-xl bg-tertiary-container text-white font-title-md text-xs font-semibold shadow-xs hover:bg-tertiary transition"
                                        >
                                            <span class="material-symbols-outlined text-[18px]">chat</span>
                                            <span>Chat Admin</span>
                                        </a>
                                    </div>

                                    <div class="flex items-center justify-center gap-4 pt-1 text-center font-spec-data text-xs text-on-surface-variant">
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[15px] text-tertiary">check</span>
                                            Harga Bersih Termasuk Pajak
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[15px] text-tertiary">check</span>
                                            Tanpa Biaya Tersembunyi
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION: ARMADA MOBIL UNGGULAN (Stitch Reference) -->
            <section id="armada-mobil" class="w-full py-16 lg:py-24 scroll-mt-20">
                <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
                    <!-- Section Header & Filter Tabs -->
                    <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-8 rounded-full bg-primary"></span>
                                <span class="font-label-badge text-xs uppercase tracking-wider text-primary font-bold">
                                    Koleksi Kendaraan Roda 4
                                </span>
                            </div>
                            <h2 class="mt-2 font-headline-lg text-2xl lg:text-3xl font-bold text-on-surface">
                                Armada Mobil Unggulan & Siap Pakai
                            </h2>
                            <p class="mt-1 font-body-md text-sm text-on-surface-variant">
                                Seluruh armada menjalani inspeksi mekanis 40 titik serta sanitasi kabin berkala sebelum serah terima.
                            </p>
                        </div>

                        <!-- Filter Chips -->
                        <div class="flex flex-wrap items-center gap-2">
                            <button
                                type="button"
                                @click="carFilter = 'all'"
                                class="rounded-lg px-4 py-2 font-title-md text-xs font-semibold transition"
                                :class="[
                                    carFilter === 'all'
                                        ? 'bg-primary text-on-primary shadow-sm'
                                        : 'bg-surface-container-high text-on-surface hover:bg-surface-variant'
                                ]"
                            >
                                Semua Mobil
                            </button>
                            <button
                                type="button"
                                @click="carFilter = 'city'"
                                class="rounded-lg px-4 py-2 font-title-md text-xs font-semibold transition"
                                :class="[
                                    carFilter === 'city'
                                        ? 'bg-primary text-on-primary shadow-sm'
                                        : 'bg-surface-container-high text-on-surface hover:bg-surface-variant'
                                ]"
                            >
                                City Car
                            </button>
                            <button
                                type="button"
                                @click="carFilter = 'mpv'"
                                class="rounded-lg px-4 py-2 font-title-md text-xs font-semibold transition"
                                :class="[
                                    carFilter === 'mpv'
                                        ? 'bg-primary text-on-primary shadow-sm'
                                        : 'bg-surface-container-high text-on-surface hover:bg-surface-variant'
                                ]"
                            >
                                Family MPV
                            </button>
                        </div>
                    </div>

                    <!-- Car Grid Matrix -->
                    <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <VehicleCard
                            v-for="car in filteredCars"
                            :key="car.id"
                            :vehicle="car"
                            @open-detail="openDetailModal"
                        />
                    </div>

                    <!-- Banner Promo Lepas Kunci (Stitch Reference) -->
                    <div class="mt-12 overflow-hidden rounded-2xl bg-gradient-to-r from-primary to-primary-container p-6 text-on-primary lg:p-8 shadow-xl">
                        <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                            <div class="space-y-1.5">
                                <span class="rounded-full bg-on-primary/20 px-3 py-1 font-label-badge text-[11px] uppercase tracking-wider text-on-primary font-bold">
                                    Promo Mingguan Spesial
                                </span>
                                <h4 class="font-headline-md text-xl lg:text-2xl font-bold text-on-primary">
                                    Sewa 5 Hari, Gratis Tambahan 1 Hari!
                                </h4>
                                <p class="font-body-md text-sm text-on-primary-container max-w-xl">
                                    Berlaku untuk rental unit Avanza, Xpander, dan Innova Zenix. Nikmati fleksibilitas ekstra untuk perjalanan dinas maupun liburan keluarga Anda.
                                </p>
                            </div>
                            <a
                                href="https://wa.me/6281288889999?text=Halo%20X-RENT,%20saya%20tertarik%20dengan%20Promo%20Mingguan%20Sewa%205%20Hari%20Gratis%201%20Hari!"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 rounded-xl bg-surface-container-lowest px-6 py-3 font-title-md text-sm font-bold text-primary shadow-md transition hover:bg-surface-container-low shrink-0"
                            >
                                <span>Klaim Promo via WA</span>
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION: ARMADA MOTOR UNGGULAN (Stitch Reference + UI-First Dummy) -->
            <section id="armada-motor" class="w-full bg-surface-container-low py-16 lg:py-24 scroll-mt-20">
                <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-8 rounded-full bg-tertiary"></span>
                                <span class="font-label-badge text-xs uppercase tracking-wider text-tertiary font-bold">
                                    Mobilitas Lincah Dua Roda
                                </span>
                            </div>
                            <h2 class="mt-2 font-headline-lg text-2xl lg:text-3xl font-bold text-on-surface">
                                Armada Motor Matic & Gaya Hidup
                            </h2>
                            <p class="mt-1 font-body-md text-sm text-on-surface-variant">
                                Semua sewa motor gratis 2 Helm SNI higienis & wangi, jas hujan ganda rangkap 2, dan holder smartphone tahan air.
                            </p>
                        </div>

                        <!-- Motor Category Filters -->
                        <div class="flex items-center gap-2 mt-4 md:mt-0">
                            <button
                                type="button"
                                @click="motorFilter = 'all'"
                                class="rounded-lg px-4 py-2 font-title-md text-xs font-semibold transition"
                                :class="[
                                    motorFilter === 'all'
                                        ? 'bg-primary text-on-primary shadow-sm'
                                        : 'bg-surface-container-high text-on-surface hover:bg-surface-variant'
                                ]"
                            >
                                Semua Motor
                            </button>
                            <button
                                type="button"
                                @click="motorFilter = 'maxi'"
                                class="rounded-lg px-4 py-2 font-title-md text-xs font-semibold transition"
                                :class="[
                                    motorFilter === 'maxi'
                                        ? 'bg-primary text-on-primary shadow-sm'
                                        : 'bg-surface-container-high text-on-surface hover:bg-surface-variant'
                                ]"
                            >
                                Maxi Scooter
                            </button>
                            <button
                                type="button"
                                @click="motorFilter = 'retro'"
                                class="rounded-lg px-4 py-2 font-title-md text-xs font-semibold transition"
                                :class="[
                                    motorFilter === 'retro'
                                        ? 'bg-primary text-on-primary shadow-sm'
                                        : 'bg-surface-container-high text-on-surface hover:bg-surface-variant'
                                ]"
                            >
                                Vespa Retro
                            </button>
                        </div>
                    </div>

                    <!-- Motor Grid Cards using VehicleCard Component -->
                    <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <VehicleCard
                            v-for="motor in filteredMotors"
                            :key="motor.id"
                            :vehicle="motor"
                            @open-detail="openDetailModal"
                        />
                    </div>
                </div>
            </section>

            <!-- SECTION: KEUNGGULAN LAYANAN X-RENT (Stitch Bento 4 Pillars) -->
            <section class="w-full py-16 lg:py-24 bg-surface">
                <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto">
                        <span class="font-label-badge text-xs uppercase tracking-wider text-primary font-bold">
                            Standar Layanan X-RENT
                        </span>
                        <h2 class="mt-2 font-headline-lg text-2xl lg:text-3xl font-bold text-on-surface">
                            Kenapa 40,000+ Pelanggan Mempercayai Kami?
                        </h2>
                        <p class="mt-2 font-body-md text-sm text-on-surface-variant">
                            Komitmen penuh pada keselamatan, transparansi harga, dan keleluasaan waktu perjalanan Anda tanpa rasa was-was.
                        </p>
                    </div>

                    <!-- 4 Pillars Bento Grid -->
                    <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <!-- Pillar 1 -->
                        <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-sm hover:shadow-md transition border border-surface-container-highest/60">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-surface-container-high text-primary mb-5">
                                <span class="material-symbols-outlined text-2xl">build_circle</span>
                            </div>
                            <h3 class="font-headline-sm text-base font-bold text-on-surface mb-2">Inspeksi 40 Titik Mekanis</h3>
                            <p class="font-body-sm text-xs text-on-surface-variant leading-relaxed">
                                Pengecekan ketebalan rem, tekanan angin ban, oli mesin, dan AC dilakukan secara teliti sebelum mobil/motor diserahterimakan.
                            </p>
                        </div>

                        <!-- Pillar 2 -->
                        <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-sm hover:shadow-md transition border border-surface-container-highest/60">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-surface-container-high text-tertiary mb-5">
                                <span class="material-symbols-outlined text-2xl">verified_user</span>
                            </div>
                            <h3 class="font-headline-sm text-base font-bold text-on-surface mb-2">Asuransi All-Risk Komprehensif</h3>
                            <p class="font-body-sm text-xs text-on-surface-variant leading-relaxed">
                                Setiap unit sudah diproteksi asuransi resmi dari risiko insiden di jalan raya tanpa premi tersembunyi yang memberatkan penyewa.
                            </p>
                        </div>

                        <!-- Pillar 3 -->
                        <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-sm hover:shadow-md transition border border-surface-container-highest/60">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-surface-container-high text-primary mb-5">
                                <span class="material-symbols-outlined text-2xl">support_agent</span>
                            </div>
                            <h3 class="font-headline-sm text-base font-bold text-on-surface mb-2">Emergency Roadside 24 Jam</h3>
                            <p class="font-body-sm text-xs text-on-surface-variant leading-relaxed">
                                Kendala aki drop, ban bocor, atau mobil mogok di jalan? Tim teknisi siaga kami segera meluncur ke lokasi Anda 24 jam nonstop.
                            </p>
                        </div>

                        <!-- Pillar 4 -->
                        <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-sm hover:shadow-md transition border border-surface-container-highest/60">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-surface-container-high text-tertiary mb-5">
                                <span class="material-symbols-outlined text-2xl">local_shipping</span>
                            </div>
                            <h3 class="font-headline-sm text-base font-bold text-on-surface mb-2">Antar-Jemput On-Time</h3>
                            <p class="font-body-sm text-xs text-on-surface-variant leading-relaxed">
                                Layanan serah terima tepat waktu langsung di lobi hotel, terminal bandara (Soetta & Halim), stasiun kereta, maupun depan pintu rumah.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION: PERSYARATAN SEWA (PRD Requirement with Stitch styling) -->
            <section id="syarat" class="w-full py-16 bg-surface-container-low scroll-mt-20 border-t border-surface-container-highest/60">
                <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto mb-12">
                        <span class="font-label-badge text-xs uppercase tracking-wider text-primary font-bold">
                            Ketentuan Mudah & Cepat
                        </span>
                        <h2 class="mt-2 font-headline-lg text-2xl lg:text-3xl font-bold text-on-surface">
                            Persyaratan Sewa Mobil & Motor
                        </h2>
                        <p class="mt-1 font-body-md text-sm text-on-surface-variant">
                            Verifikasi data hanya butuh 10-15 menit via WhatsApp. Tanpa form rumit dan tanpa penahanan dokumen asli.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Box 1: Motor (SIM C & KTP) -->
                        <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-sm border border-surface-container-highest/60 space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold">
                                    <span class="material-symbols-outlined">two_wheeler</span>
                                </div>
                                <div>
                                    <h3 class="font-title-md font-bold text-on-surface text-sm">Rental Motor</h3>
                                    <span class="text-[11px] text-tertiary font-semibold">Khusus Lepas Kunci</span>
                                </div>
                            </div>

                            <ul class="space-y-2.5 text-xs text-on-surface-variant">
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-tertiary shrink-0 mt-0.5">check_circle</span>
                                    <span><strong>E-KTP Asli</strong> penyewa yang masih aktif</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-tertiary shrink-0 mt-0.5">check_circle</span>
                                    <span><strong>SIM C Aktif</strong> resmi dari Kepolisian RI</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-tertiary shrink-0 mt-0.5">check_circle</span>
                                    <span>Bukti tiket perjalanan / ID Karyawan / Akun Sosmed</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-tertiary shrink-0 mt-0.5">check_circle</span>
                                    <span>Deposit jaminan (refundable 100% saat pengembalian)</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Box 2: Mobil Lepas Kunci -->
                        <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-sm border border-surface-container-highest/60 space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold">
                                    <span class="material-symbols-outlined">directions_car</span>
                                </div>
                                <div>
                                    <h3 class="font-title-md font-bold text-on-surface text-sm">Mobil Lepas Kunci</h3>
                                    <span class="text-[11px] text-primary font-semibold">Self-Drive 24 Jam</span>
                                </div>
                            </div>

                            <ul class="space-y-2.5 text-xs text-on-surface-variant">
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-tertiary shrink-0 mt-0.5">check_circle</span>
                                    <span><strong>E-KTP Asli</strong> & KK / PBB / ID Kantor</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-tertiary shrink-0 mt-0.5">check_circle</span>
                                    <span><strong>SIM A Aktif</strong> yang sah</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-tertiary shrink-0 mt-0.5">check_circle</span>
                                    <span>Akun media sosial aktif (Instagram / LinkedIn)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-tertiary shrink-0 mt-0.5">check_circle</span>
                                    <span>Deposit jaminan unit (refundable setelah sewa)</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Box 3: Mobil Dengan Supir -->
                        <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-sm border border-surface-container-highest/60 space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-tertiary/10 text-tertiary flex items-center justify-center font-bold">
                                    <span class="material-symbols-outlined">person</span>
                                </div>
                                <div>
                                    <h3 class="font-title-md font-bold text-on-surface text-sm">Mobil + Supir</h3>
                                    <span class="text-[11px] text-tertiary font-semibold">Paling Praktis & Santai</span>
                                </div>
                            </div>

                            <ul class="space-y-2.5 text-xs text-on-surface-variant">
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-tertiary shrink-0 mt-0.5">check_circle</span>
                                    <span><strong>Cukup E-KTP</strong> atau identitas pemesan</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-tertiary shrink-0 mt-0.5">check_circle</span>
                                    <span>Informasi jadwal & rute titik penjemputan</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-tertiary shrink-0 mt-0.5">check_circle</span>
                                    <span><strong>Tanpa Deposit Jaminan Unit</strong> sama sekali</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-tertiary shrink-0 mt-0.5">check_circle</span>
                                    <span>Supir ramah, rapi, tersertifikasi & hapal jalan</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION: FAQ ACCORDION INTERAKTIF (PRD Requirement) -->
            <section id="faq" class="w-full py-16 lg:py-24 bg-surface scroll-mt-20">
                <div class="mx-auto max-w-[860px] px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-10">
                        <span class="font-label-badge text-xs uppercase tracking-wider text-primary font-bold">
                            Tanya Jawab Populer
                        </span>
                        <h2 class="mt-2 font-headline-lg text-2xl lg:text-3xl font-bold text-on-surface">
                            Pertanyaan Seputar Sewa di X-RENT
                        </h2>
                    </div>

                    <!-- Accordion Items -->
                    <div class="space-y-3.5">
                        <div
                            v-for="(faq, idx) in faqs"
                            :key="idx"
                            class="rounded-2xl bg-surface-container-lowest border border-surface-container-highest/70 overflow-hidden transition-all shadow-xs"
                        >
                            <button
                                type="button"
                                @click="toggleFaq(idx)"
                                class="w-full flex items-center justify-between p-5 text-left font-title-md text-sm font-bold text-on-surface hover:text-primary transition"
                            >
                                <span class="flex items-center gap-3">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-surface-container-high text-xs font-bold text-primary shrink-0">
                                        {{ idx + 1 }}
                                    </span>
                                    <span>{{ faq.q }}</span>
                                </span>
                                <span
                                    class="material-symbols-outlined text-on-surface-variant transition-transform duration-200"
                                    :class="openFaqIndex === idx ? 'rotate-180 text-primary' : ''"
                                >
                                    expand_more
                                </span>
                            </button>

                            <div
                                v-show="openFaqIndex === idx"
                                class="px-5 pb-5 pt-0 text-xs text-on-surface-variant leading-relaxed border-t border-surface-container-low mt-1"
                            >
                                <p class="pt-3">{{ faq.a }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- MODAL DETAIL UNIT -->
        <div
            v-if="selectedDetailVehicle"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            @click.self="closeDetailModal"
        >
            <div class="relative w-full max-w-lg rounded-3xl bg-surface-container-lowest p-6 shadow-2xl border border-surface-container-highest max-h-[90vh] overflow-y-auto">
                <button
                    type="button"
                    @click="closeDetailModal"
                    class="absolute top-4 right-4 p-2 rounded-full hover:bg-surface-container-low text-on-surface-variant transition"
                >
                    ✕
                </button>

                <div class="relative h-48 rounded-2xl overflow-hidden bg-surface-container-low mb-4">
                    <img :src="selectedDetailVehicle.image" :alt="selectedDetailVehicle.name" class="w-full h-full object-cover" />
                    <span class="absolute top-3 left-3 rounded-full bg-primary px-3 py-1 font-label-badge text-[11px] text-on-primary font-bold">
                        {{ selectedDetailVehicle.category_label || selectedDetailVehicle.type }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <h3 class="font-headline-sm text-xl font-bold text-on-surface">
                        {{ selectedDetailVehicle.name }}
                    </h3>
                    <span class="font-headline-sm text-lg font-bold text-primary">
                        {{ formatRupiah(selectedDetailPrice) }}
                    </span>
                </div>

                <p class="text-xs text-on-surface-variant mt-1">
                    Tahun {{ selectedDetailVehicle.year }} • Opsi: {{ selectedDetailVehicle.driver_option }}
                </p>

                <!-- Features list -->
                <div class="mt-4 pt-4 border-t border-surface-container-highest">
                    <p class="font-title-md text-xs font-bold text-on-surface mb-2">Fitur & Fasilitas Termasuk:</p>
                    <ul class="grid grid-cols-2 gap-2 text-xs text-on-surface-variant">
                        <li
                            v-for="(feat, fIdx) in selectedDetailVehicle.features"
                            :key="fIdx"
                            class="flex items-center gap-1.5"
                        >
                            <span class="material-symbols-outlined text-[16px] text-tertiary">check</span>
                            <span>{{ feat }}</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button
                        type="button"
                        @click="closeDetailModal"
                        class="flex-1 py-3 rounded-xl bg-surface-container-high text-xs font-bold text-on-surface hover:bg-surface-variant transition"
                    >
                        Tutup
                    </button>
                    <a
                        :href="`https://wa.me/6281288889999?text=${encodeURIComponent('Halo X-RENT, saya ingin sewa unit ' + selectedDetailVehicle.name)}`"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex-1 py-3 rounded-xl bg-primary text-xs font-bold text-on-primary text-center hover:bg-primary-container shadow-md transition"
                    >
                        Pesan via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
