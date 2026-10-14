<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

const isMobileMenuOpen = ref(false);
const isScrolled = ref(false);
const showWaTooltip = ref(true);

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const closeMobileMenu = () => {
    isMobileMenuOpen.value = false;
};

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const navLinks = [
    { label: 'Beranda', href: '/', isRoute: true },
    { label: 'Katalog Semua', href: '/katalog', isRoute: true },
    { label: 'Armada Mobil', href: '/armada-mobil', isRoute: true },
    { label: 'Armada Motor', href: '/armada-motor', isRoute: true },
    { label: 'Cek Booking', href: '/cek-pesanan', isRoute: true },
    { label: 'Syarat & Ketentuan', href: '/#syarat', isRoute: false },
    { label: 'Lokasi Garasi', href: '/#lokasi', isRoute: false },
    { label: 'FAQ', href: '/#faq', isRoute: false },
];

const isActive = (href) => {
    if (href === '/') return page.url === '/';
    return page.url.startsWith(href);
};

const waNumber = '6281288889999';
const waMessage = encodeURIComponent('Halo X-RENT, saya ingin tanya ketersediaan rental kendaraan (mobil/motor).');
const waUrl = `https://wa.me/${waNumber}?text=${waMessage}`;
</script>

<template>
    <div class="min-h-screen flex flex-col bg-surface font-body-md text-on-surface antialiased selection:bg-primary selection:text-on-primary">
        <!-- Top Informative Header Bar (Google Stitch Reference) -->
        <header class="fixed top-0 left-0 right-0 z-50 bg-surface-container-lowest/95 backdrop-blur-xl shadow-[0_1px_8px_rgba(11,28,48,0.06)] border-b border-surface-container-highest/60">
            <!-- Top Utility Bar (Desktop) -->
            <div class="hidden lg:block bg-surface-container-low border-b border-surface-container-highest/40">
                <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 h-10 flex items-center justify-between font-spec-data text-[12px] text-on-surface-variant">
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-primary">pin_drop</span>
                            <span>Garasi: Cilandak Jaksel & Bandara Soetta</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-tertiary-container">call</span>
                            <span>Hotline: (021) 7888-9999 / 0812-8888-9999</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1.5 text-tertiary font-semibold">
                            <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
                            Buka Setiap Hari (06.00 - 23.00 WIB)
                        </span>
                        <span class="text-outline-variant">|</span>
                        <span class="text-on-surface-variant font-medium">CS Darurat 24 Jam</span>
                    </div>
                </div>
            </div>

            <!-- Main Navbar -->
            <div class="h-20 max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
                <!-- Brand Logo -->
                <Link href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-primary to-primary-container flex items-center justify-center text-white shadow-md shadow-primary/20 group-hover:scale-105 transition-transform duration-200">
                        <span class="material-symbols-outlined text-[24px]">directions_car</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-headline-sm text-xl font-bold tracking-tight text-primary leading-none">
                            X-RENT<span class="text-tertiary">.</span>
                        </span>
                        <span class="font-label-badge text-[10px] tracking-wider text-on-surface-variant uppercase mt-1">
                            Rental Kendaraan
                        </span>
                    </div>
                </Link>

                <!-- Desktop Nav Links -->
                <nav class="hidden xl:flex items-center gap-6 text-[15px]">
                    <template v-for="link in navLinks" :key="link.label">
                        <Link
                            v-if="link.isRoute"
                            :href="link.href"
                            class="transition-colors py-1 font-medium relative"
                            :class="[
                                isActive(link.href)
                                    ? 'text-primary font-bold after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:bg-primary after:rounded-full'
                                    : 'text-on-surface-variant hover:text-primary'
                            ]"
                        >
                            {{ link.label }}
                        </Link>
                        <a
                            v-else
                            :href="link.href"
                            class="text-on-surface-variant hover:text-primary transition-colors py-1 font-medium"
                        >
                            {{ link.label }}
                        </a>
                    </template>
                </nav>

                <!-- Navbar Action CTAs -->
                <div class="flex items-center gap-3">
                    <Link
                        href="/katalog"
                        class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-container-high text-primary hover:bg-surface-variant font-title-md text-sm font-semibold transition-colors"
                    >
                        <span class="material-symbols-outlined text-[18px]">search</span>
                        <span>Cari Unit</span>
                    </Link>

                    <a
                        :href="waUrl"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-on-primary hover:bg-primary-container font-title-md text-sm font-semibold shadow-sm transition active:scale-95"
                    >
                        <span class="material-symbols-outlined text-[18px]">key</span>
                        <span>Sewa Cepat</span>
                    </a>

                    <!-- Mobile Menu Button -->
                    <button
                        type="button"
                        @click="toggleMobileMenu"
                        class="xl:hidden p-2 rounded-xl text-on-surface hover:bg-surface-container-low transition focus:outline-none"
                        aria-label="Toggle navigation menu"
                    >
                        <span class="material-symbols-outlined text-[26px]">
                            {{ isMobileMenuOpen ? 'close' : 'menu' }}
                        </span>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Drawer -->
            <transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <div
                    v-if="isMobileMenuOpen"
                    class="xl:hidden bg-surface-container-lowest border-b border-surface-container-highest px-4 pt-3 pb-6 shadow-xl"
                >
                    <nav class="flex flex-col gap-1">
                        <template v-for="link in navLinks" :key="link.label">
                            <Link
                                v-if="link.isRoute"
                                :href="link.href"
                                @click="closeMobileMenu"
                                class="px-4 py-3 rounded-xl text-base font-semibold transition flex items-center justify-between"
                                :class="[
                                    isActive(link.href)
                                        ? 'bg-primary/10 text-primary font-bold'
                                        : 'text-on-surface hover:text-primary hover:bg-surface-container-low'
                                ]"
                            >
                                <span>{{ link.label }}</span>
                                <span v-if="isActive(link.href)" class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                            </Link>
                            <a
                                v-else
                                :href="link.href"
                                @click="closeMobileMenu"
                                class="px-4 py-3 rounded-xl text-base font-semibold text-on-surface hover:text-primary hover:bg-surface-container-low transition"
                            >
                                {{ link.label }}
                            </a>
                        </template>
                    </nav>

                    <div class="mt-4 pt-4 border-t border-surface-container-highest flex flex-col gap-3">
                        <div class="p-3 bg-surface-container-low rounded-xl text-xs text-on-surface-variant flex items-start gap-2">
                            <span class="material-symbols-outlined text-[18px] text-primary shrink-0">pin_drop</span>
                            <div>
                                <p class="font-bold text-on-surface">Garasi Cilandak & Bandara Soetta</p>
                                <p>Buka 06.00 - 23.00 WIB • Hotline: 0812-8888-9999</p>
                            </div>
                        </div>

                        <a
                            :href="waUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            @click="closeMobileMenu"
                            class="w-full flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-base font-bold bg-primary text-on-primary hover:bg-primary-container shadow-md transition"
                        >
                            <span class="material-symbols-outlined text-[20px]">chat</span>
                            <span>Chat WhatsApp 24 Jam</span>
                        </a>
                    </div>
                </div>
            </transition>
        </header>

        <!-- Main Content Area -->
        <main class="w-full pt-20 lg:pt-[120px] bg-surface min-h-[calc(100vh-80px)] flex-1">
            <slot />
        </main>

        <!-- Floating WhatsApp Button (Google Stitch Reference) -->
        <aside class="fixed bottom-6 right-6 z-50 flex items-center group pointer-events-none">
            <!-- Tooltip Speech Bubble -->
            <transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0 translate-y-2 scale-95"
                enter-to-class="opacity-100 translate-y-0 scale-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100 translate-y-0 scale-100"
                leave-to-class="opacity-0 translate-y-2 scale-95"
            >
                <div
                    v-if="showWaTooltip"
                    class="pointer-events-auto mr-3 hidden sm:flex items-center gap-2 bg-surface-container-lowest text-on-surface shadow-[0_10px_20px_rgba(0,0,0,0.08)] px-3.5 py-2.5 rounded-xl border border-surface-container-highest/60"
                >
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="font-spec-data text-xs text-on-surface font-semibold">
                        Chat WhatsApp X-RENT (Fast Response 24/7)
                    </span>
                    <button
                        type="button"
                        @click="showWaTooltip = false"
                        class="text-on-surface-variant hover:text-on-surface ml-1 text-xs"
                    >
                        ✕
                    </button>
                </div>
            </transition>

            <!-- Gradient WhatsApp Button with Pulse Ring -->
            <a
                :href="waUrl"
                target="_blank"
                rel="noopener noreferrer"
                class="pointer-events-auto relative w-14 h-14 rounded-full bg-gradient-to-br from-[#25D366] to-[#128C7E] text-white flex items-center justify-center shadow-[0_14px_28px_-4px_rgba(37,211,102,0.45)] hover:scale-105 active:scale-95 transition-transform duration-200"
                aria-label="Chat WhatsApp X-RENT"
            >
                <span class="absolute inset-0 rounded-full bg-[#25D366] opacity-30 animate-ping pointer-events-none"></span>
                <span class="material-symbols-outlined text-3xl relative z-10">chat</span>
            </a>
        </aside>

        <!-- Footer (Google Stitch Reference) -->
        <footer class="w-full bg-surface-container-low mt-16 border-t border-surface-container-highest/60">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8">
                    <!-- Col 1: Brand Info -->
                    <div class="lg:col-span-4 space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center text-white">
                                <span class="material-symbols-outlined text-[22px]">directions_car</span>
                            </div>
                            <span class="font-headline-sm text-2xl font-bold text-primary">X-RENT</span>
                        </div>
                        <p class="font-body-md text-sm text-on-surface-variant max-w-sm leading-relaxed">
                            Solusi Rental Mobil & Motor Terpercaya, Lepas Kunci & All-In Supir. Unit prima, higienis, dan terproteksi asuransi komprehensif.
                        </p>
                        <div class="flex flex-col gap-1.5 font-spec-data text-xs text-on-surface-variant pt-2">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-tertiary-container text-base">verified</span>
                                Badan Usaha Resmi: PT X-RENT Mobilitas Indonesia
                            </span>
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-base">verified_user</span>
                                Asuransi All-Risk & Bebas Biaya Tersembunyi
                            </span>
                        </div>
                    </div>

                    <!-- Col 2: Alamat Garasi -->
                    <div class="lg:col-span-3 space-y-3">
                        <h3 class="font-title-md text-base font-bold text-on-surface">Alamat Garasi</h3>
                        <div class="space-y-3 font-body-sm text-xs text-on-surface-variant">
                            <div class="p-3.5 rounded-xl bg-surface-container-lowest border border-surface-container-highest/60">
                                <p class="font-title-md text-on-surface font-bold text-sm">Garasi Pusat (Cilandak):</p>
                                <p class="mt-1 leading-relaxed">Jl. Raya Otomotif No. 88, Cilandak, Jakarta Selatan 12430</p>
                            </div>
                            <div class="p-3.5 rounded-xl bg-surface-container-lowest border border-surface-container-highest/60">
                                <p class="font-title-md text-on-surface font-bold text-sm">Garasi Cabang:</p>
                                <p class="mt-1 leading-relaxed">Perimeter Bandara Soekarno-Hatta (T2 & T3) & BSD City Hub</p>
                            </div>
                        </div>
                    </div>

                    <!-- Col 3: Jam Operasional -->
                    <div class="lg:col-span-3 space-y-3">
                        <h3 class="font-title-md text-base font-bold text-on-surface">Jam Operasional</h3>
                        <div class="space-y-3 font-body-sm text-xs text-on-surface-variant">
                            <div class="p-3.5 rounded-xl bg-surface-container-lowest border border-surface-container-highest/60">
                                <p class="font-title-md text-on-surface font-bold text-sm">Layanan Rental & Garasi:</p>
                                <p class="mt-1">Buka Setiap Hari (06.00 - 23.00 WIB)</p>
                            </div>
                            <div class="p-3.5 rounded-xl bg-surface-container-lowest border border-surface-container-highest/60">
                                <p class="font-title-md text-on-surface font-bold text-sm">Layanan Darurat & CS WhatsApp:</p>
                                <p class="mt-1 text-tertiary font-bold text-sm flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
                                    24 Jam Non-Stop Siaga
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Col 4: Menu Utama -->
                    <div class="lg:col-span-2 space-y-3">
                        <h3 class="font-title-md text-base font-bold text-on-surface">Menu Utama</h3>
                        <ul class="space-y-2 font-body-sm text-xs text-on-surface-variant">
                            <li><Link class="hover:text-primary transition-colors" href="/">Beranda</Link></li>
                            <li><Link class="hover:text-primary transition-colors" href="/katalog">Katalog Lengkap</Link></li>
                            <li><Link class="hover:text-primary transition-colors" href="/armada-mobil">Armada Mobil</Link></li>
                            <li><Link class="hover:text-primary transition-colors" href="/armada-motor">Armada Motor</Link></li>
                            <li><Link class="hover:text-primary transition-colors" href="/cek-pesanan">Cek Status Pesanan</Link></li>
                            <li><a class="hover:text-primary transition-colors" href="/#syarat">Ketentuan Rental</a></li>
                            <li><a class="hover:text-primary transition-colors" href="/#lokasi">Lokasi Garasi</a></li>
                            <li><a class="hover:text-primary transition-colors" href="/#faq">Pusat Bantuan / FAQ</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Payment Methods Section (Stitch Reference) -->
                <div class="mt-10 bg-surface-container-lowest rounded-2xl p-6 border border-surface-container-highest/60">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex flex-col text-center md:text-left">
                            <span class="font-label-badge text-xs uppercase text-on-surface-variant font-bold tracking-wider">
                                Metode Pembayaran Resmi
                            </span>
                            <span class="font-body-sm text-xs text-on-surface-variant mt-0.5">
                                Pembayaran aman melalui Virtual Account, QRIS, & Kartu Kredit berlisensi
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center justify-center gap-2.5 font-spec-data text-xs font-bold">
                            <span class="px-3 py-1.5 rounded-lg bg-surface text-primary border border-surface-container-highest">BCA</span>
                            <span class="px-3 py-1.5 rounded-lg bg-surface text-primary border border-surface-container-highest">MANDIRI</span>
                            <span class="px-3 py-1.5 rounded-lg bg-surface text-primary border border-surface-container-highest">BRI</span>
                            <span class="px-3 py-1.5 rounded-lg bg-surface text-primary border border-surface-container-highest">BNI</span>
                            <span class="px-3 py-1.5 rounded-lg bg-surface text-on-surface border border-surface-container-highest">QRIS</span>
                            <span class="px-3 py-1.5 rounded-lg bg-surface text-on-surface-variant border border-surface-container-highest">VISA / MASTERCARD</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom Copyright -->
                <div class="mt-8 pt-6 border-t border-surface-container-highest/60 flex flex-col md:flex-row items-center justify-between gap-4 font-body-sm text-xs text-on-surface-variant">
                    <p>© 2026 PT X-RENT Mobilitas Indonesia. Hak cipta dilindungi undang-undang.</p>
                    <div class="flex items-center gap-6">
                        <a class="hover:text-primary transition" href="/#syarat">Syarat Layanan</a>
                        <span>•</span>
                        <a class="hover:text-primary transition" href="/#syarat">Kebijakan Privasi</a>
                        <span>•</span>
                        <a class="hover:text-primary transition" :href="waUrl" target="_blank" rel="noopener noreferrer">Bantuan CS 24 Jam</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
