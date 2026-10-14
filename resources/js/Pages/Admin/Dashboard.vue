<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    stats: {
        type: Object,
        required: true,
    },
    recentBookings: {
        type: Array,
        default: () => [],
    },
    recentVehicles: {
        type: Array,
        default: () => [],
    },
});

const formatRupiah = (val) => {
    if (val === undefined || val === null) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(val);
};

const getStatusBadge = (status) => {
    switch (status) {
        case 'pending':
            return { bg: 'bg-amber-100 text-amber-800', label: 'Menunggu Konfirmasi' };
        case 'confirmed':
            return { bg: 'bg-blue-100 text-blue-800', label: 'Dikonfirmasi (Siap)' };
        case 'ongoing':
            return { bg: 'bg-emerald-100 text-emerald-800', label: 'Sedang Disewa' };
        case 'completed':
            return { bg: 'bg-slate-100 text-slate-800', label: 'Selesai & Refund' };
        case 'cancelled':
            return { bg: 'bg-red-100 text-red-800', label: 'Dibatalkan' };
        default:
            return { bg: 'bg-slate-100 text-slate-800', label: status };
    }
};

const getVehicleStatusBadge = (status) => {
    switch (status) {
        case 'available':
            return { bg: 'bg-emerald-100 text-emerald-800', label: 'Tersedia' };
        case 'rented':
            return { bg: 'bg-amber-100 text-amber-800', label: 'Sedang Disewa' };
        case 'maintenance':
            return { bg: 'bg-red-100 text-red-800', label: 'Servis / Bengkel' };
        default:
            return { bg: 'bg-slate-100 text-slate-800', label: status };
    }
};
</script>

<template>
    <Head title="Dashboard Admin | X-RENT" />

    <AdminLayout>
        <template #header>
            <div>
                <h1 class="font-headline-sm text-lg sm:text-xl font-bold text-on-surface">
                    Ringkasan Operasional (Dashboard)
                </h1>
                <p class="font-body-sm text-xs text-on-surface-variant">
                    Pantau statistik armada, pemesanan pelanggan, dan aktivitas garasi X-RENT.
                </p>
            </div>
        </template>

        <!-- TOP METRICS GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Card 1: Total Armada -->
            <div class="p-5 rounded-3xl bg-surface-container-lowest border border-surface-container-highest shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="font-title-md text-xs font-bold text-on-surface-variant uppercase tracking-wider">
                        Total Armada
                    </span>
                    <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">directions_car</span>
                    </div>
                </div>
                <div>
                    <h3 class="font-headline-lg text-2xl sm:text-3xl font-extrabold text-on-surface">
                        {{ stats.total_vehicles }} <span class="text-xs font-normal text-on-surface-variant">Unit</span>
                    </h3>
                    <div class="pt-2 flex items-center gap-3 text-xs font-spec-data text-on-surface-variant">
                        <span class="text-emerald-700 font-semibold">• {{ stats.available_vehicles }} Tersedia</span>
                        <span class="text-amber-700 font-semibold">• {{ stats.rented_vehicles }} Disewa</span>
                        <span class="text-red-700 font-semibold">• {{ stats.maintenance_vehicles }} Servis</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Pesanan Masuk (Pending) -->
            <div class="p-5 rounded-3xl bg-surface-container-lowest border border-surface-container-highest shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="font-title-md text-xs font-bold text-on-surface-variant uppercase tracking-wider">
                        Perlu Konfirmasi
                    </span>
                    <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">pending_actions</span>
                    </div>
                </div>
                <div>
                    <h3 class="font-headline-lg text-2xl sm:text-3xl font-extrabold text-amber-700">
                        {{ stats.pending_bookings }} <span class="text-xs font-normal text-on-surface-variant">Pesanan</span>
                    </h3>
                    <p class="pt-2 text-xs font-spec-data text-on-surface-variant">
                        {{ stats.confirmed_bookings }} pesanan siap diambil / diantar
                    </p>
                </div>
            </div>

            <!-- Card 3: Unit Aktif di Jalan -->
            <div class="p-5 rounded-3xl bg-surface-container-lowest border border-surface-container-highest shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="font-title-md text-xs font-bold text-on-surface-variant uppercase tracking-wider">
                        Sedang Digunakan
                    </span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">commute</span>
                    </div>
                </div>
                <div>
                    <h3 class="font-headline-lg text-2xl sm:text-3xl font-extrabold text-emerald-700">
                        {{ stats.ongoing_bookings }} <span class="text-xs font-normal text-on-surface-variant">Unit Aktif</span>
                    </h3>
                    <p class="pt-2 text-xs font-spec-data text-on-surface-variant">
                        {{ stats.completed_bookings }} transaksi telah selesai sukses
                    </p>
                </div>
            </div>

            <!-- Card 4: Total Omzet -->
            <div class="p-5 rounded-3xl bg-surface-container-lowest border border-surface-container-highest shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="font-title-md text-xs font-bold text-on-surface-variant uppercase tracking-wider">
                        Estimasi Omzet
                    </span>
                    <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">payments</span>
                    </div>
                </div>
                <div>
                    <h3 class="font-headline-lg text-xl sm:text-2xl font-extrabold text-primary truncate">
                        {{ formatRupiah(stats.total_revenue) }}
                    </h3>
                    <p class="pt-2 text-xs font-spec-data text-on-surface-variant">
                        Dari {{ stats.total_bookings }} total transaksi terdaftar
                    </p>
                </div>
            </div>
        </div>

        <!-- QUICK ACTION SHORTCUTS -->
        <div class="p-5 rounded-3xl bg-gradient-to-r from-primary to-primary-container text-white shadow-md flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="space-y-1 text-center md:text-left">
                <h3 class="font-headline-sm text-base sm:text-lg font-bold">
                    Pintasan Manajemen Operasional
                </h3>
                <p class="text-xs text-white/80">
                    Akses cepat ke pengelolaan unit motor/mobil dan respon pesanan pelanggan WhatsApp.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <Link
                    href="/admin/vehicles"
                    class="px-4 py-2.5 rounded-xl bg-white text-primary font-title-md text-xs font-bold shadow-xs hover:bg-slate-50 transition active:scale-95 flex items-center gap-2"
                >
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    <span>Kelola & Tambah Unit</span>
                </Link>
                <Link
                    href="/admin/bookings"
                    class="px-4 py-2.5 rounded-xl bg-white/20 text-white hover:bg-white/30 font-title-md text-xs font-bold transition flex items-center gap-2"
                >
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    <span>Daftar Pesanan ({{ stats.pending_bookings }} Pending)</span>
                </Link>
            </div>
        </div>

        <!-- TWO COLUMNS: RECENT BOOKINGS & FLEET SUMMARY -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- RECENT BOOKINGS TABLE (7 COLS) -->
            <div class="lg:col-span-7 rounded-3xl bg-surface-container-lowest border border-surface-container-highest p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-surface-container-highest/60">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px] text-primary">receipt_long</span>
                        <h3 class="font-title-md text-sm font-bold text-on-surface">
                            Pesanan Terbaru Masuk
                        </h3>
                    </div>
                    <Link
                        href="/admin/bookings"
                        class="text-xs font-semibold text-primary hover:underline flex items-center gap-1"
                    >
                        <span>Lihat Semua</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </Link>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-spec-data">
                        <thead>
                            <tr class="border-b border-surface-container-highest text-on-surface-variant font-bold">
                                <th class="pb-3">Kode Booking</th>
                                <th class="pb-3">Pelanggan</th>
                                <th class="pb-3">Unit</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container-highest/40">
                            <tr
                                v-for="b in recentBookings"
                                :key="b.id"
                                class="hover:bg-surface-container-low/50 transition"
                            >
                                <td class="py-3 font-bold text-primary">
                                    <Link :href="`/admin/bookings?q=${b.booking_code}`">
                                        {{ b.booking_code }}
                                    </Link>
                                </td>
                                <td class="py-3">
                                    <p class="font-bold text-on-surface">{{ b.customer_name }}</p>
                                    <p class="text-[11px] text-on-surface-variant">{{ b.customer_phone }}</p>
                                </td>
                                <td class="py-3">
                                    <p class="font-semibold text-on-surface">{{ b.vehicle?.name }}</p>
                                    <p class="text-[10px] text-on-surface-variant">{{ b.vehicle?.license_plate }}</p>
                                </td>
                                <td class="py-3">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                        :class="getStatusBadge(b.status).bg"
                                    >
                                        {{ getStatusBadge(b.status).label }}
                                    </span>
                                </td>
                                <td class="py-3 text-right font-bold text-on-surface">
                                    {{ formatRupiah(b.total_price) }}
                                </td>
                            </tr>
                            <tr v-if="recentBookings.length === 0">
                                <td colspan="5" class="py-6 text-center text-on-surface-variant">
                                    Belum ada transaksi pemesanan masuk.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- FLEET QUICK STATUS (5 COLS) -->
            <div class="lg:col-span-5 rounded-3xl bg-surface-container-lowest border border-surface-container-highest p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-surface-container-highest/60">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px] text-primary">two_wheeler</span>
                        <h3 class="font-title-md text-sm font-bold text-on-surface">
                            Armada Kendaraan X-RENT
                        </h3>
                    </div>
                    <Link
                        href="/admin/vehicles"
                        class="text-xs font-semibold text-primary hover:underline flex items-center gap-1"
                    >
                        <span>Kelola Armada</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </Link>
                </div>

                <div class="space-y-3">
                    <div
                        v-for="v in recentVehicles"
                        :key="v.id"
                        class="flex items-center gap-3 p-3 rounded-2xl bg-surface-container-low border border-surface-container-highest/60"
                    >
                        <img
                            :src="v.image"
                            :alt="v.name"
                            class="w-14 h-12 rounded-xl object-cover shadow-xs shrink-0"
                        />
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="font-title-md text-xs font-bold text-on-surface truncate">
                                    {{ v.name }}
                                </h4>
                                <span
                                    class="px-2 py-0.5 rounded-full text-[9px] font-bold shrink-0"
                                    :class="getVehicleStatusBadge(v.status).bg"
                                >
                                    {{ getVehicleStatusBadge(v.status).label }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] font-spec-data text-on-surface-variant pt-0.5">
                                <span>Plat: {{ v.license_plate }}</span>
                                <span class="font-bold text-primary">{{ formatRupiah(v.price_daily) }} / hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
