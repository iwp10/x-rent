<script setup>
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    vehicles: {
        type: Array,
        default: () => [],
    },
    categories: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

// Search & Filter State
const searchInput = ref(props.filters.q || '');
const selectedStatus = ref(props.filters.status || 'all');
const selectedType = ref(props.filters.type || 'all');
const selectedCategory = ref(props.filters.kategori || 'all');

const applyFilters = () => {
    router.get('/admin/vehicles', {
        q: searchInput.value || undefined,
        status: selectedStatus.value !== 'all' ? selectedStatus.value : undefined,
        type: selectedType.value !== 'all' ? selectedType.value : undefined,
        kategori: selectedCategory.value !== 'all' ? selectedCategory.value : undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const resetFilters = () => {
    searchInput.value = '';
    selectedStatus.value = 'all';
    selectedType.value = 'all';
    selectedCategory.value = 'all';
    applyFilters();
};

// Quick Status Update
const updatingStatusId = ref(null);
const updateVehicleStatus = (vehicle, newStatus) => {
    if (vehicle.status === newStatus) return;
    updatingStatusId.value = vehicle.id;
    router.patch(`/admin/vehicles/${vehicle.id}/status`, {
        status: newStatus,
    }, {
        preserveScroll: true,
        onFinish: () => {
            updatingStatusId.value = null;
        },
    });
};

// Modal State: Create / Edit
const showModal = ref(false);
const isEditing = ref(false);

const form = useForm({
    id: null,
    category_id: props.categories[0]?.id || '',
    name: '',
    license_plate: '',
    engine_cc: 110,
    transmission: 'Matic CVT',
    helm_included: 2,
    status: 'available',
    price_daily: 85000,
    price_weekly: 530000,
    price_monthly: 1750000,
    image: '',
    type: 'motor',
    brand: 'Honda',
    year: '2024',
    condition_notes: '',
    facilities: '',
});

const openAddModal = () => {
    isEditing.value = false;
    form.reset();
    form.category_id = props.categories[0]?.id || '';
    form.type = 'motor';
    form.status = 'available';
    form.helm_included = 2;
    form.transmission = 'Matic CVT';
    showModal.value = true;
};

const openEditModal = (v) => {
    isEditing.value = true;
    form.id = v.id;
    form.category_id = v.category_id;
    form.name = v.name;
    form.license_plate = v.license_plate;
    form.engine_cc = v.engine_cc;
    form.transmission = v.transmission;
    form.helm_included = v.helm_included;
    form.status = v.status;
    form.price_daily = v.price_daily;
    form.price_weekly = v.price_weekly;
    form.price_monthly = v.price_monthly;
    form.image = v.image || '';
    form.type = v.type || 'motor';
    form.brand = v.brand || '';
    form.year = v.year || '2024';
    form.condition_notes = v.condition_notes || '';
    form.facilities = v.facilities || '';
    showModal.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(`/admin/vehicles/${form.id}`, {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    } else {
        form.post('/admin/vehicles', {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    }
};

const deleteVehicle = (v) => {
    if (confirm(`Apakah Anda yakin ingin menghapus unit '${v.name}' (Plat: ${v.license_plate}) dari database?`)) {
        router.delete(`/admin/vehicles/${v.id}`, {
            preserveScroll: true,
        });
    }
};

const formatRupiah = (val) => {
    if (val === undefined || val === null) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(val);
};
</script>

<template>
    <Head title="Manajemen Armada Kendaraan | Panel Admin X-RENT" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between w-full">
                <div>
                    <h1 class="font-headline-sm text-lg sm:text-xl font-bold text-on-surface">
                        Manajemen Armada Kendaraan
                    </h1>
                    <p class="font-body-sm text-xs text-on-surface-variant">
                        Kelola katalog motor & mobil, tambah unit baru, atur tarif sewa, dan ubah status ketersediaan.
                    </p>
                </div>
                <button
                    type="button"
                    @click="openAddModal"
                    class="px-4 py-2.5 rounded-xl bg-primary text-on-primary font-title-md text-xs sm:text-sm font-bold shadow-sm hover:bg-primary-container transition active:scale-95 flex items-center gap-2"
                >
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span class="hidden sm:inline">Tambah Unit Baru</span>
                    <span class="sm:hidden">Tambah</span>
                </button>
            </div>
        </template>

        <!-- FILTERS & SEARCH BAR -->
        <div class="rounded-3xl bg-surface-container-lowest border border-surface-container-highest p-4 sm:p-5 shadow-xs space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <!-- Search Input -->
                <div class="lg:col-span-2 relative flex items-center">
                    <span class="absolute left-3 text-on-surface-variant material-symbols-outlined text-[18px]">
                        search
                    </span>
                    <input
                        v-model="searchInput"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Cari nama unit atau plat nomor..."
                        class="w-full pl-9 pr-3 py-2 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none transition"
                    />
                </div>

                <!-- Type Filter -->
                <div>
                    <select
                        v-model="selectedType"
                        @change="applyFilters"
                        class="w-full py-2 px-3 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none transition"
                    >
                        <option value="all">Semua Tipe (Motor & Mobil)</option>
                        <option value="motor">Khusus Armada Motor</option>
                        <option value="mobil">Khusus Armada Mobil</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select
                        v-model="selectedStatus"
                        @change="applyFilters"
                        class="w-full py-2 px-3 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none transition"
                    >
                        <option value="all">Semua Status</option>
                        <option value="available">Tersedia (Ready)</option>
                        <option value="rented">Sedang Disewa</option>
                        <option value="maintenance">Dalam Perawatan</option>
                    </select>
                </div>

                <!-- Action Filter Buttons -->
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        @click="applyFilters"
                        class="flex-1 py-2 px-3 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary-container transition"
                    >
                        Terapkan
                    </button>
                    <button
                        type="button"
                        @click="resetFilters"
                        class="py-2 px-3 rounded-xl bg-surface-container-high text-on-surface text-xs font-semibold hover:bg-surface-variant transition"
                        title="Reset Filter"
                    >
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- FLEET TABLE CARD -->
        <div class="rounded-3xl bg-surface-container-lowest border border-surface-container-highest shadow-xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-surface-container-highest/60 flex items-center justify-between">
                <div class="flex items-center gap-2 font-spec-data text-xs text-on-surface-variant">
                    <span>Menampilkan <strong>{{ vehicles.length }}</strong> unit armada</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-spec-data">
                    <thead class="bg-surface-container-low/50 text-on-surface-variant font-bold border-b border-surface-container-highest">
                        <tr>
                            <th class="py-3 px-4">Unit Kendaraan</th>
                            <th class="py-3 px-3">Kategori & Spesifikasi</th>
                            <th class="py-3 px-3">Tarif Sewa (Harian / Mgg / Bln)</th>
                            <th class="py-3 px-3">Status Ketersediaan</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-container-highest/40">
                        <tr
                            v-for="v in vehicles"
                            :key="v.id"
                            class="hover:bg-surface-container-low/40 transition"
                        >
                            <!-- Unit Info -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <img
                                        :src="v.image"
                                        :alt="v.name"
                                        class="w-14 h-12 object-cover rounded-xl shadow-xs shrink-0"
                                    />
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <span class="px-1.5 py-0.5 rounded-md bg-surface-container-high text-on-surface text-[10px] font-bold">
                                                {{ v.type === 'mobil' ? 'Mobil' : 'Motor' }}
                                            </span>
                                            <span class="px-1.5 py-0.5 rounded-md bg-primary/10 text-primary text-[10px] font-bold">
                                                {{ v.license_plate }}
                                            </span>
                                        </div>
                                        <p class="font-title-md text-xs sm:text-sm font-bold text-on-surface truncate pt-0.5">
                                            {{ v.name }}
                                        </p>
                                        <p class="text-[11px] text-on-surface-variant">
                                            Brand: {{ v.brand || 'Honda' }} • Th: {{ v.year || '2024' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Category & Specs -->
                            <td class="py-3 px-3">
                                <p class="font-bold text-on-surface">{{ v.category?.name || '-' }}</p>
                                <p class="text-[11px] text-on-surface-variant">
                                    {{ v.engine_cc }} cc • {{ v.transmission }} • {{ v.helm_included }} Helm
                                </p>
                            </td>

                            <!-- Price Breakdown -->
                            <td class="py-3 px-3 space-y-0.5">
                                <p class="font-bold text-primary">{{ formatRupiah(v.price_daily) }} <span class="text-[10px] font-normal text-on-surface-variant">/ hari</span></p>
                                <p class="text-[11px] text-on-surface-variant">{{ formatRupiah(v.price_weekly) }} / minggu</p>
                                <p class="text-[11px] text-on-surface-variant">{{ formatRupiah(v.price_monthly) }} / bulan</p>
                            </td>

                            <!-- Status Dropdown (Instant Update) -->
                            <td class="py-3 px-3">
                                <div class="relative inline-block w-40">
                                    <select
                                        :value="v.status"
                                        :disabled="updatingStatusId === v.id"
                                        @change="updateVehicleStatus(v, $event.target.value)"
                                        class="w-full py-1.5 pl-2.5 pr-6 rounded-xl text-xs font-bold border transition cursor-pointer disabled:opacity-50"
                                        :class="[
                                            v.status === 'available'
                                                ? 'bg-emerald-50 text-emerald-800 border-emerald-300'
                                                : v.status === 'rented'
                                                ? 'bg-amber-50 text-amber-800 border-amber-300'
                                                : 'bg-red-50 text-red-800 border-red-300'
                                        ]"
                                    >
                                        <option value="available">Tersedia (Ready)</option>
                                        <option value="rented">Sedang Disewa</option>
                                        <option value="maintenance">Dalam Perawatan</option>
                                    </select>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <Link
                                        :href="`/armada/${v.slug}`"
                                        target="_blank"
                                        class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition"
                                        title="Buka Halaman Publik"
                                    >
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </Link>
                                    <button
                                        type="button"
                                        @click="openEditModal(v)"
                                        class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition"
                                        title="Edit Data Unit"
                                    >
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button
                                        type="button"
                                        @click="deleteVehicle(v)"
                                        class="p-1.5 rounded-lg text-on-surface-variant hover:text-red-600 hover:bg-red-50 transition"
                                        title="Hapus Unit"
                                    >
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="vehicles.length === 0">
                            <td colspan="5" class="py-12 text-center text-on-surface-variant space-y-2">
                                <span class="material-symbols-outlined text-4xl text-on-surface-variant/40">two_wheeler</span>
                                <p class="text-sm font-semibold">Tidak ada unit kendaraan yang sesuai filter.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL TAMBAH / EDIT KENDARAAN -->
        <div
            v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        >
            <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-3xl bg-surface-container-lowest p-6 sm:p-8 shadow-2xl border border-surface-container-highest space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-surface-container-highest/60">
                    <div>
                        <h3 class="font-headline-sm text-lg font-bold text-on-surface">
                            {{ isEditing ? 'Edit Data Kendaraan' : 'Tambah Unit Armada Baru' }}
                        </h3>
                        <p class="font-body-sm text-xs text-on-surface-variant">
                            Lengkapi informasi spesifikasi dan tarif sewa unit X-RENT.
                        </p>
                    </div>
                    <button
                        type="button"
                        @click="showModal = false"
                        class="text-on-surface-variant hover:text-on-surface p-1"
                    >
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div class="space-y-1 sm:col-span-2">
                            <label class="block font-spec-data text-xs font-semibold text-on-surface">
                                Nama Lengkap Unit *
                            </label>
                            <input
                                v-model="form.name"
                                type="text"
                                required
                                placeholder="Contoh: Honda Vario 160 ABS"
                                class="w-full px-3 py-2 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none"
                            />
                        </div>

                        <!-- Type -->
                        <div class="space-y-1">
                            <label class="block font-spec-data text-xs font-semibold text-on-surface">
                                Tipe Kendaraan *
                            </label>
                            <select
                                v-model="form.type"
                                class="w-full px-3 py-2 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none"
                            >
                                <option value="motor">Sepeda Motor</option>
                                <option value="mobil">Mobil</option>
                            </select>
                        </div>

                        <!-- Category -->
                        <div class="space-y-1">
                            <label class="block font-spec-data text-xs font-semibold text-on-surface">
                                Kategori Unit *
                            </label>
                            <select
                                v-model="form.category_id"
                                required
                                class="w-full px-3 py-2 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none"
                            >
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                    {{ cat.name }}
                                </option>
                            </select>
                        </div>

                        <!-- License Plate -->
                        <div class="space-y-1">
                            <label class="block font-spec-data text-xs font-semibold text-on-surface">
                                Plat Nomor Polisi *
                            </label>
                            <input
                                v-model="form.license_plate"
                                type="text"
                                required
                                placeholder="Contoh: B 4821 KTF"
                                class="w-full px-3 py-2 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none"
                            />
                        </div>

                        <!-- CC Engine -->
                        <div class="space-y-1">
                            <label class="block font-spec-data text-xs font-semibold text-on-surface">
                                Kapasitas Mesin (CC) *
                            </label>
                            <input
                                v-model.number="form.engine_cc"
                                type="number"
                                required
                                placeholder="110"
                                class="w-full px-3 py-2 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none"
                            />
                        </div>

                        <!-- Transmission -->
                        <div class="space-y-1">
                            <label class="block font-spec-data text-xs font-semibold text-on-surface">
                                Tipe Transmisi *
                            </label>
                            <input
                                v-model="form.transmission"
                                type="text"
                                required
                                placeholder="Matic CVT / Manual 5-Speed"
                                class="w-full px-3 py-2 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none"
                            />
                        </div>

                        <!-- Helm Count -->
                        <div class="space-y-1">
                            <label class="block font-spec-data text-xs font-semibold text-on-surface">
                                Helm Termasuk (Motor)
                            </label>
                            <input
                                v-model.number="form.helm_included"
                                type="number"
                                min="0"
                                max="10"
                                placeholder="2"
                                class="w-full px-3 py-2 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none"
                            />
                        </div>

                        <!-- Status Initial -->
                        <div class="space-y-1">
                            <label class="block font-spec-data text-xs font-semibold text-on-surface">
                                Status Ketersediaan Awal *
                            </label>
                            <select
                                v-model="form.status"
                                class="w-full px-3 py-2 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none"
                            >
                                <option value="available">Tersedia (Ready)</option>
                                <option value="rented">Sedang Disewa</option>
                                <option value="maintenance">Dalam Perawatan (Bengkel)</option>
                            </select>
                        </div>

                        <!-- Brand -->
                        <div class="space-y-1">
                            <label class="block font-spec-data text-xs font-semibold text-on-surface">
                                Brand / Pabrikan
                            </label>
                            <input
                                v-model="form.brand"
                                type="text"
                                placeholder="Honda / Yamaha / Toyota"
                                class="w-full px-3 py-2 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none"
                            />
                        </div>

                        <!-- Price Daily -->
                        <div class="space-y-1">
                            <label class="block font-spec-data text-xs font-semibold text-on-surface">
                                Tarif Harian (Rp) *
                            </label>
                            <input
                                v-model.number="form.price_daily"
                                type="number"
                                required
                                class="w-full px-3 py-2 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none"
                            />
                        </div>

                        <!-- Price Weekly -->
                        <div class="space-y-1">
                            <label class="block font-spec-data text-xs font-semibold text-on-surface">
                                Tarif Mingguan (Rp) *
                            </label>
                            <input
                                v-model.number="form.price_weekly"
                                type="number"
                                required
                                class="w-full px-3 py-2 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none"
                            />
                        </div>

                        <!-- Price Monthly -->
                        <div class="space-y-1">
                            <label class="block font-spec-data text-xs font-semibold text-on-surface">
                                Tarif Bulanan (Rp) *
                            </label>
                            <input
                                v-model.number="form.price_monthly"
                                type="number"
                                required
                                class="w-full px-3 py-2 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none"
                            />
                        </div>

                        <!-- Photo URL -->
                        <div class="space-y-1 sm:col-span-2">
                            <label class="block font-spec-data text-xs font-semibold text-on-surface">
                                URL Foto Unit
                            </label>
                            <input
                                v-model="form.image"
                                type="url"
                                placeholder="https://images.unsplash.com/..."
                                class="w-full px-3 py-2 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none"
                            />
                        </div>

                        <!-- Condition Notes -->
                        <div class="space-y-1 sm:col-span-2">
                            <label class="block font-spec-data text-xs font-semibold text-on-surface">
                                Catatan Kondisi Fisik & Servis
                            </label>
                            <textarea
                                v-model="form.condition_notes"
                                rows="2"
                                placeholder="Kondisi ban tubeless baru, servis berkala dealer resmi, bodi mulus..."
                                class="w-full px-3 py-2 rounded-xl bg-surface-container-low text-xs text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-4 border-t border-surface-container-highest/60 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="showModal = false"
                            class="px-4 py-2.5 rounded-xl bg-surface-container-high text-on-surface text-xs font-semibold hover:bg-surface-variant transition"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-5 py-2.5 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary-container transition active:scale-95 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : (isEditing ? 'Simpan Perubahan' : 'Tambahkan Unit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
