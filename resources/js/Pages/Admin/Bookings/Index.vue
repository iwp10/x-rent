<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    bookings: {
        type: Array,
        default: () => [],
    },
    statusCounts: {
        type: Object,
        default: () => ({}),
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const searchInput = ref(props.filters.q || '');
const activeStatusTab = ref(props.filters.status || 'all');

const applyStatusFilter = (status) => {
    activeStatusTab.value = status;
    router.get('/admin/bookings', {
        status: status !== 'all' ? status : undefined,
        q: searchInput.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const handleSearch = () => {
    router.get('/admin/bookings', {
        status: activeStatusTab.value !== 'all' ? activeStatusTab.value : undefined,
        q: searchInput.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

// Quick Status Update
const updatingBookingId = ref(null);
const updateBookingStatus = (booking, newStatus) => {
    if (booking.status === newStatus) return;
    updatingBookingId.value = booking.id;
    router.patch(`/admin/bookings/${booking.id}/status`, {
        status: newStatus,
    }, {
        preserveScroll: true,
        onFinish: () => {
            updatingBookingId.value = null;
        },
    });
};

// Modal Detail View
const selectedBooking = ref(null);
const showDetailModal = ref(false);

const openDetailModal = (b) => {
    selectedBooking.value = b;
    showDetailModal.value = true;
};

const formatRupiah = (val) => {
    if (val === undefined || val === null) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(val);
};

const getWhatsAppCustomerUrl = (b) => {
    let cleanPhone = b.customer_phone.replace(/\D/g, '');
    if (cleanPhone.startsWith('0')) {
        cleanPhone = '62' + cleanPhone.substring(1);
    }
    const msg = `Halo Kak ${b.customer_name}, kami dari Admin X-RENT ingin mengonfirmasi status pemesanan kendaraan dengan Kode Booking *${b.booking_code}* (${b.vehicle?.name}).`;
    return `https://wa.me/${cleanPhone}?text=${encodeURIComponent(msg)}`;
};
</script>

<template>
    <Head title="Manajemen Pesanan Masuk | Panel Admin X-RENT" />

    <AdminLayout>
        <template #header>
            <div>
                <h1 class="font-headline-sm text-lg sm:text-xl font-bold text-on-surface">
                    Manajemen Pesanan Masuk (Bookings)
                </h1>
                <p class="font-body-sm text-xs text-on-surface-variant">
                    Verifikasi identitas pelanggan, kelola alur reservasi unit, dan perbarui status sewa.
                </p>
            </div>
        </template>

        <!-- STATUS TABS (ALL / PENDING / CONFIRMED / ONGOING / COMPLETED / CANCELLED) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-title-md font-semibold">
            <button
                type="button"
                @click="applyStatusFilter('all')"
                class="px-3.5 py-2 rounded-xl transition shrink-0 flex items-center gap-1.5 border"
                :class="[
                    activeStatusTab === 'all'
                        ? 'bg-primary text-white border-primary shadow-xs font-bold'
                        : 'bg-surface-container-lowest text-on-surface border-surface-container-highest hover:bg-surface-container-low'
                ]"
            >
                <span>Semua Pesanan</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="activeStatusTab === 'all' ? 'bg-white/20 text-white' : 'bg-surface-container-high text-on-surface-variant'">
                    {{ statusCounts.all || 0 }}
                </span>
            </button>

            <button
                type="button"
                @click="applyStatusFilter('pending')"
                class="px-3.5 py-2 rounded-xl transition shrink-0 flex items-center gap-1.5 border"
                :class="[
                    activeStatusTab === 'pending'
                        ? 'bg-amber-600 text-white border-amber-600 shadow-xs font-bold'
                        : 'bg-surface-container-lowest text-on-surface border-surface-container-highest hover:bg-amber-50'
                ]"
            >
                <span>Menunggu Konfirmasi</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-amber-100 text-amber-800 font-bold">
                    {{ statusCounts.pending || 0 }}
                </span>
            </button>

            <button
                type="button"
                @click="applyStatusFilter('confirmed')"
                class="px-3.5 py-2 rounded-xl transition shrink-0 flex items-center gap-1.5 border"
                :class="[
                    activeStatusTab === 'confirmed'
                        ? 'bg-blue-600 text-white border-blue-600 shadow-xs font-bold'
                        : 'bg-surface-container-lowest text-on-surface border-surface-container-highest hover:bg-blue-50'
                ]"
            >
                <span>Dikonfirmasi (Siap)</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-blue-100 text-blue-800 font-bold">
                    {{ statusCounts.confirmed || 0 }}
                </span>
            </button>

            <button
                type="button"
                @click="applyStatusFilter('ongoing')"
                class="px-3.5 py-2 rounded-xl transition shrink-0 flex items-center gap-1.5 border"
                :class="[
                    activeStatusTab === 'ongoing'
                        ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs font-bold'
                        : 'bg-surface-container-lowest text-on-surface border-surface-container-highest hover:bg-emerald-50'
                ]"
            >
                <span>Sedang Disewa</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-emerald-100 text-emerald-800 font-bold">
                    {{ statusCounts.ongoing || 0 }}
                </span>
            </button>

            <button
                type="button"
                @click="applyStatusFilter('completed')"
                class="px-3.5 py-2 rounded-xl transition shrink-0 flex items-center gap-1.5 border"
                :class="[
                    activeStatusTab === 'completed'
                        ? 'bg-slate-700 text-white border-slate-700 shadow-xs font-bold'
                        : 'bg-surface-container-lowest text-on-surface border-surface-container-highest hover:bg-surface-container-low'
                ]"
            >
                <span>Selesai & Refund</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-slate-200 text-slate-800 font-bold">
                    {{ statusCounts.completed || 0 }}
                </span>
            </button>

            <button
                type="button"
                @click="applyStatusFilter('cancelled')"
                class="px-3.5 py-2 rounded-xl transition shrink-0 flex items-center gap-1.5 border"
                :class="[
                    activeStatusTab === 'cancelled'
                        ? 'bg-red-600 text-white border-red-600 shadow-xs font-bold'
                        : 'bg-surface-container-lowest text-on-surface border-surface-container-highest hover:bg-red-50'
                ]"
            >
                <span>Dibatalkan</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-red-100 text-red-800 font-bold">
                    {{ statusCounts.cancelled || 0 }}
                </span>
            </button>
        </div>

        <!-- SEARCH INPUT -->
        <div class="rounded-3xl bg-surface-container-lowest border border-surface-container-highest p-4 shadow-xs">
            <form @submit.prevent="handleSearch" class="flex items-center gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-3.5 text-on-surface-variant material-symbols-outlined text-[18px]">
                        search
                    </span>
                    <input
                        v-model="searchInput"
                        type="text"
                        placeholder="Cari berdasarkan Kode Booking (XR-...), Nama Pemesan, atau Nomor WhatsApp..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none transition"
                    />
                </div>
                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary-container transition shrink-0"
                >
                    Cari
                </button>
            </form>
        </div>

        <!-- BOOKINGS TABLE -->
        <div class="rounded-3xl bg-surface-container-lowest border border-surface-container-highest shadow-xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-surface-container-highest/60 flex items-center justify-between">
                <span class="font-spec-data text-xs text-on-surface-variant">
                    Menampilkan <strong>{{ bookings.length }}</strong> transaksi pemesanan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-spec-data">
                    <thead class="bg-surface-container-low/50 text-on-surface-variant font-bold border-b border-surface-container-highest">
                        <tr>
                            <th class="py-3 px-4">Kode & Waktu</th>
                            <th class="py-3 px-3">Data Pemesan</th>
                            <th class="py-3 px-3">Unit Kendaraan</th>
                            <th class="py-3 px-3">Jadwal Sewa</th>
                            <th class="py-3 px-3">Nilai Transaksi</th>
                            <th class="py-3 px-3">Ubah Status</th>
                            <th class="py-3 px-4 text-right">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-container-highest/40">
                        <tr
                            v-for="b in bookings"
                            :key="b.id"
                            class="hover:bg-surface-container-low/40 transition"
                        >
                            <!-- Code & Date -->
                            <td class="py-3 px-4">
                                <Link
                                    :href="`/cek-pesanan?code=${b.booking_code}`"
                                    target="_blank"
                                    class="font-bold text-primary hover:underline text-xs"
                                    title="Lacak di Halaman Publik"
                                >
                                    {{ b.booking_code }}
                                </Link>
                                <p class="text-[10px] text-on-surface-variant">
                                    {{ new Date(b.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}
                                </p>
                            </td>

                            <!-- Customer -->
                            <td class="py-3 px-3">
                                <p class="font-bold text-on-surface">{{ b.customer_name }}</p>
                                <div class="flex items-center gap-1.5 pt-0.5">
                                    <span class="text-on-surface-variant">{{ b.customer_phone }}</span>
                                    <a
                                        :href="getWhatsAppCustomerUrl(b)"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-tertiary hover:opacity-80 p-0.5"
                                        title="Chat WhatsApp Pelanggan"
                                    >
                                        <span class="material-symbols-outlined text-[14px]">chat</span>
                                    </a>
                                </div>
                                <span class="px-1.5 py-0.2 rounded bg-surface-container-high text-[9px] font-semibold text-on-surface-variant uppercase">
                                    {{ b.customer_type }}
                                </span>
                            </td>

                            <!-- Vehicle -->
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-2">
                                    <img
                                        :src="b.vehicle?.image"
                                        :alt="b.vehicle?.name"
                                        class="w-10 h-8 rounded-lg object-cover shadow-xs shrink-0"
                                    />
                                    <div class="min-w-0">
                                        <p class="font-bold text-on-surface truncate">{{ b.vehicle?.name }}</p>
                                        <p class="text-[10px] text-primary font-bold">{{ b.vehicle?.license_plate }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Schedule -->
                            <td class="py-3 px-3 space-y-0.5">
                                <p class="font-semibold text-on-surface">
                                    {{ new Date(b.start_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}
                                </p>
                                <p class="text-[10px] text-on-surface-variant">
                                    Durasi: <strong>{{ b.duration_count }} {{ b.duration_type }}</strong>
                                </p>
                                <span class="text-[10px] font-medium" :class="b.delivery_option === 'delivery' ? 'text-tertiary font-bold' : 'text-on-surface-variant'">
                                    {{ b.delivery_option === 'delivery' ? '• Antar Drop-off' : '• Ambil di Garasi' }}
                                </span>
                            </td>

                            <!-- Total -->
                            <td class="py-3 px-3 space-y-0.5">
                                <p class="font-bold text-primary">{{ formatRupiah(b.total_price) }}</p>
                                <span
                                    class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold"
                                    :class="b.payment_status?.includes('Lunas') || b.status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'"
                                >
                                    {{ b.payment_status || 'Belum Lunas' }}
                                </span>
                            </td>

                            <!-- Interactive Status Change -->
                            <td class="py-3 px-3">
                                <div class="relative inline-block w-40">
                                    <select
                                        :value="b.status"
                                        :disabled="updatingBookingId === b.id"
                                        @change="updateBookingStatus(b, $event.target.value)"
                                        class="w-full py-1.5 pl-2.5 pr-6 rounded-xl text-xs font-bold border transition cursor-pointer disabled:opacity-50"
                                        :class="[
                                            b.status === 'pending'
                                                ? 'bg-amber-50 text-amber-800 border-amber-300'
                                                : b.status === 'confirmed'
                                                ? 'bg-blue-50 text-blue-800 border-blue-300'
                                                : b.status === 'ongoing'
                                                ? 'bg-emerald-50 text-emerald-800 border-emerald-300'
                                                : b.status === 'completed'
                                                ? 'bg-slate-100 text-slate-800 border-slate-300'
                                                : 'bg-red-50 text-red-800 border-red-300'
                                        ]"
                                    >
                                        <option value="pending">1. Menunggu Konfirmasi</option>
                                        <option value="confirmed">2. Dikonfirmasi (Siap)</option>
                                        <option value="ongoing">3. Sedang Disewa</option>
                                        <option value="completed">4. Selesai & Refund</option>
                                        <option value="cancelled">X. Dibatalkan</option>
                                    </select>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right">
                                <button
                                    type="button"
                                    @click="openDetailModal(b)"
                                    class="p-1.5 rounded-lg text-primary hover:bg-primary/10 transition"
                                    title="Lihat Detail Pesanan"
                                >
                                    <span class="material-symbols-outlined text-[18px]">info</span>
                                </button>
                            </td>
                        </tr>

                        <tr v-if="bookings.length === 0">
                            <td colspan="7" class="py-12 text-center text-on-surface-variant space-y-2">
                                <span class="material-symbols-outlined text-4xl text-on-surface-variant/40">receipt_long</span>
                                <p class="text-sm font-semibold">Tidak ada pesanan masuk untuk status ini.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL DETAIL PESANAN -->
        <div
            v-if="showDetailModal && selectedBooking"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        >
            <div class="relative w-full max-w-lg rounded-3xl bg-surface-container-lowest p-6 sm:p-7 shadow-2xl border border-surface-container-highest space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-surface-container-highest/60">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-headline-sm text-base font-bold text-primary">
                                {{ selectedBooking.booking_code }}
                            </span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                :class="selectedBooking.status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'"
                            >
                                {{ selectedBooking.status_label }}
                            </span>
                        </div>
                        <p class="font-body-sm text-[11px] text-on-surface-variant">
                            Dibuat: {{ new Date(selectedBooking.created_at).toLocaleString('id-ID') }}
                        </p>
                    </div>
                    <button
                        type="button"
                        @click="showDetailModal = false"
                        class="text-on-surface-variant hover:text-on-surface p-1"
                    >
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <!-- Info Grid -->
                <div class="space-y-3 font-spec-data text-xs">
                    <div class="p-3.5 rounded-2xl bg-surface-container-low space-y-1">
                        <span class="text-on-surface-variant font-medium">Unit Armada:</span>
                        <p class="font-bold text-on-surface text-sm">{{ selectedBooking.vehicle?.name }}</p>
                        <p class="text-primary font-semibold">Plat Polisi: {{ selectedBooking.vehicle?.license_plate }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 rounded-xl bg-surface-container-low space-y-0.5">
                            <span class="text-on-surface-variant">Nama Pemesan:</span>
                            <p class="font-bold text-on-surface">{{ selectedBooking.customer_name }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-surface-container-low space-y-0.5">
                            <span class="text-on-surface-variant">WhatsApp:</span>
                            <p class="font-bold text-primary">{{ selectedBooking.customer_phone }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 rounded-xl bg-surface-container-low space-y-0.5">
                            <span class="text-on-surface-variant">Mulai Sewa:</span>
                            <p class="font-bold text-on-surface">{{ new Date(selectedBooking.start_date).toLocaleString('id-ID') }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-surface-container-low space-y-0.5">
                            <span class="text-on-surface-variant">Durasi:</span>
                            <p class="font-bold text-on-surface">{{ selectedBooking.duration_count }} {{ selectedBooking.duration_type }}</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-surface-container-low space-y-0.5">
                        <span class="text-on-surface-variant">Metode Pengambilan & Alamat:</span>
                        <p class="font-bold text-on-surface">
                            {{ selectedBooking.delivery_option === 'delivery' ? 'Antar-Jemput ke Lokasi (+Rp 25.000)' : 'Ambil di Garasi Pusat (Gratis)' }}
                        </p>
                        <p class="text-[11px] text-on-surface-variant">{{ selectedBooking.pickup_address }}</p>
                    </div>

                    <div v-if="selectedBooking.notes" class="p-3 rounded-xl bg-surface-container-low space-y-0.5">
                        <span class="text-on-surface-variant">Catatan Tambahan Pelanggan:</span>
                        <p class="italic text-on-surface">"{{ selectedBooking.notes }}"</p>
                    </div>

                    <!-- Financial Total -->
                    <div class="p-3.5 rounded-2xl bg-primary/5 border border-primary/20 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] text-on-surface-variant">Total Biaya Pemesanan:</span>
                            <p class="font-headline-sm text-base font-extrabold text-primary">
                                {{ formatRupiah(selectedBooking.total_price) }}
                            </p>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-surface-container-lowest text-xs font-bold border border-surface-container-highest">
                            {{ selectedBooking.payment_status || 'Belum Lunas' }}
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-2 flex items-center gap-3">
                    <a
                        :href="getWhatsAppCustomerUrl(selectedBooking)"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex-1 py-2.5 px-4 rounded-xl bg-primary text-white font-title-md text-xs font-bold text-center hover:bg-primary-container transition flex items-center justify-center gap-2"
                    >
                        <span class="material-symbols-outlined text-[18px]">chat</span>
                        <span>Chat WhatsApp Pelanggan</span>
                    </a>
                    <Link
                        :href="`/cek-pesanan?code=${selectedBooking.booking_code}`"
                        target="_blank"
                        class="py-2.5 px-4 rounded-xl bg-surface-container-high text-on-surface font-title-md text-xs font-semibold hover:bg-surface-variant transition"
                    >
                        Halaman Publik
                    </Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
