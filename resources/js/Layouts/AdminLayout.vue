<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';

const page = usePage();
const isSidebarOpen = ref(false);

const adminUser = computed(() => page.props.auth?.user || {
    name: 'Admin X-RENT',
    email: 'admin@x-rent.test',
});

const navLinks = [
    {
        label: 'Dashboard Overview',
        href: '/admin/dashboard',
        icon: 'dashboard',
    },
    {
        label: 'Manajemen Armada',
        href: '/admin/vehicles',
        icon: 'two_wheeler',
    },
    {
        label: 'Pesanan Pelanggan',
        href: '/admin/bookings',
        icon: 'receipt_long',
    },
];

const isActive = (href) => {
    return page.url.startsWith(href);
};

const handleLogout = () => {
    router.post('/admin/logout');
};
</script>

<template>
    <div class="min-h-screen flex bg-surface font-body-md text-on-surface antialiased">
        <!-- BACKDROP FOR MOBILE SIDEBAR -->
        <div
            v-if="isSidebarOpen"
            @click="isSidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-xs lg:hidden"
        ></div>

        <!-- SIDEBAR NAVIGATION (GOOGLE STITCH REFERENCE) -->
        <aside
            class="fixed top-0 bottom-0 left-0 z-50 w-64 bg-surface-container-lowest border-r border-surface-container-highest/70 flex flex-col transition-transform duration-200 lg:translate-x-0"
            :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <!-- Sidebar Header / Brand -->
            <div class="h-20 px-6 flex items-center justify-between border-b border-surface-container-highest/60">
                <Link href="/admin/dashboard" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-primary to-primary-container flex items-center justify-center text-white shadow-md shadow-primary/20">
                        <span class="material-symbols-outlined text-[24px]">directions_car</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-headline-sm text-lg font-bold tracking-tight text-primary leading-none">
                            X-RENT<span class="text-tertiary">.</span>
                        </span>
                        <span class="font-label-badge text-[10px] tracking-wider text-on-surface-variant uppercase mt-1">
                            Panel Pengelola
                        </span>
                    </div>
                </Link>

                <button
                    type="button"
                    @click="isSidebarOpen = false"
                    class="lg:hidden text-on-surface-variant hover:text-on-surface p-1"
                >
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Sidebar Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <div class="px-3 pb-2">
                    <span class="font-label-badge text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">
                        Menu Utama
                    </span>
                </div>

                <Link
                    v-for="link in navLinks"
                    :key="link.href"
                    :href="link.href"
                    @click="isSidebarOpen = false"
                    class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs sm:text-sm font-semibold transition group"
                    :class="[
                        isActive(link.href)
                            ? 'bg-primary text-white shadow-sm font-bold'
                            : 'text-on-surface hover:text-primary hover:bg-surface-container-low'
                    ]"
                >
                    <span
                        class="material-symbols-outlined text-[20px] transition-transform group-hover:scale-110"
                        :class="isActive(link.href) ? 'text-white' : 'text-primary'"
                    >
                        {{ link.icon }}
                    </span>
                    <span>{{ link.label }}</span>
                </Link>

                <div class="pt-6 px-3 pb-2">
                    <span class="font-label-badge text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">
                        Situs Publik
                    </span>
                </div>

                <Link
                    href="/"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-on-surface hover:text-primary hover:bg-surface-container-low transition group"
                >
                    <span class="material-symbols-outlined text-[20px] text-tertiary">storefront</span>
                    <span>Lihat Halaman Beranda</span>
                </Link>

                <Link
                    href="/katalog"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-on-surface hover:text-primary hover:bg-surface-container-low transition group"
                >
                    <span class="material-symbols-outlined text-[20px] text-tertiary">search</span>
                    <span>Katalog Publik</span>
                </Link>

                <Link
                    href="/cek-pesanan"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-on-surface hover:text-primary hover:bg-surface-container-low transition group"
                >
                    <span class="material-symbols-outlined text-[20px] text-tertiary">qr_code_scanner</span>
                    <span>Lacak Pesanan Publik</span>
                </Link>
            </nav>

            <!-- Sidebar User & Logout -->
            <div class="p-4 border-t border-surface-container-highest/60 bg-surface-container-low/40">
                <div class="flex items-center gap-3 p-2 rounded-xl bg-surface-container-lowest border border-surface-container-highest/60 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">
                        {{ adminUser.name.charAt(0) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-title-md text-xs font-bold text-on-surface truncate">
                            {{ adminUser.name }}
                        </p>
                        <p class="font-spec-data text-[10px] text-on-surface-variant truncate">
                            {{ adminUser.email }}
                        </p>
                    </div>
                </div>

                <button
                    type="button"
                    @click="handleLogout"
                    class="w-full py-2 px-3 rounded-xl bg-surface-container-high text-red-600 hover:bg-red-50 hover:text-red-700 font-title-md text-xs font-semibold transition flex items-center justify-center gap-2"
                >
                    <span class="material-symbols-outlined text-[16px]">logout</span>
                    <span>Keluar (Logout)</span>
                </button>
            </div>
        </aside>

        <!-- MAIN CONTENT WRAPPER -->
        <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">
            <!-- TOP ADMIN HEADER BAR -->
            <header class="h-16 bg-surface-container-lowest/90 backdrop-blur-md border-b border-surface-container-highest/60 px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="isSidebarOpen = true"
                        class="lg:hidden p-2 rounded-xl text-on-surface hover:bg-surface-container-low transition"
                    >
                        <span class="material-symbols-outlined text-[22px]">menu</span>
                    </button>
                    <slot name="header">
                        <h2 class="font-headline-sm text-base sm:text-lg font-bold text-on-surface">
                            Panel Pengelola X-RENT
                        </h2>
                    </slot>
                </div>

                <!-- Top Right Status -->
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full bg-tertiary/10 text-tertiary border border-tertiary/20 text-xs font-spec-data font-semibold">
                        <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
                        <span>Garasi Buka • Sistem Normal</span>
                    </div>

                    <a
                        href="https://wa.me/6281288889999"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-surface-container-low text-on-surface hover:text-primary text-xs font-semibold transition border border-surface-container-highest"
                    >
                        <span class="material-symbols-outlined text-[16px] text-tertiary">chat</span>
                        <span>Hotline CS</span>
                    </a>
                </div>
            </header>

            <!-- FLASH ALERTS -->
            <div v-if="page.props.flash?.success" class="max-w-[1400px] mx-auto w-full px-4 sm:px-6 lg:px-8 pt-4">
                <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-900 border border-emerald-200 text-xs sm:text-sm font-medium flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-[20px] text-emerald-600">check_circle</span>
                        <span>{{ page.props.flash.success }}</span>
                    </div>
                </div>
            </div>

            <div v-if="page.props.flash?.error" class="max-w-[1400px] mx-auto w-full px-4 sm:px-6 lg:px-8 pt-4">
                <div class="p-4 rounded-2xl bg-red-50 text-red-900 border border-red-200 text-xs sm:text-sm font-medium flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-[20px] text-red-600">error</span>
                        <span>{{ page.props.flash.error }}</span>
                    </div>
                </div>
            </div>

            <!-- PAGE CONTENT SLOT -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-[1400px] w-full mx-auto space-y-6">
                <slot />
            </main>
        </div>
    </div>
</template>
