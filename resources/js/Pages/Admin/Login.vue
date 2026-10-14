<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';

const form = useForm({
    email: 'admin@x-rent.test',
    password: 'password',
    remember: true,
});

const submit = () => {
    form.post('/admin/login', {
        onFinish: () => form.reset('password'),
    });
};

const fillSample = (email, pwd) => {
    form.email = email;
    form.password = pwd;
};
</script>

<template>
    <Head title="Login Admin | X-RENT" />

    <div class="min-h-screen flex items-center justify-center bg-surface font-body-md text-on-surface p-4 sm:p-6">
        <div class="w-full max-w-md space-y-6">
            <!-- Brand Header -->
            <div class="text-center space-y-2">
                <Link href="/" class="inline-flex items-center gap-2 group">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-primary to-primary-container flex items-center justify-center text-white shadow-lg shadow-primary/20 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-[26px]">admin_panel_settings</span>
                    </div>
                    <div class="text-left">
                        <span class="font-headline-sm text-2xl font-extrabold tracking-tight text-primary leading-none">
                            X-RENT<span class="text-tertiary">.</span>
                        </span>
                        <span class="block font-label-badge text-[10px] tracking-wider text-on-surface-variant uppercase mt-0.5">
                            Panel Pengelola
                        </span>
                    </div>
                </Link>
                <h1 class="font-headline-lg text-xl sm:text-2xl font-bold text-on-surface pt-2">
                    Masuk ke Dashboard Admin
                </h1>
                <p class="font-body-sm text-xs text-on-surface-variant">
                    Kelola ketersediaan armada, status pemesanan, dan operasional rental.
                </p>
            </div>

            <!-- Login Card -->
            <div class="rounded-3xl bg-surface-container-lowest p-6 sm:p-8 border border-surface-container-highest shadow-xl shadow-slate-900/5 space-y-5">
                <!-- Error Alert -->
                <div
                    v-if="form.errors.email"
                    class="p-3.5 rounded-xl bg-red-50 text-red-700 border border-red-200 text-xs flex items-center gap-2.5"
                >
                    <span class="material-symbols-outlined text-[18px] shrink-0">error</span>
                    <span>{{ form.errors.email }}</span>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <!-- Email Field -->
                    <div class="space-y-1">
                        <label for="email" class="block font-spec-data text-xs font-semibold text-on-surface">
                            Alamat Email Admin
                        </label>
                        <div class="relative flex items-center">
                            <span class="absolute left-3.5 text-on-surface-variant material-symbols-outlined text-[18px]">
                                mail
                            </span>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                autocomplete="username"
                                placeholder="admin@x-rent.test"
                                class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:bg-surface-container-lowest focus:outline-none transition"
                            />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1">
                        <label for="password" class="block font-spec-data text-xs font-semibold text-on-surface">
                            Password
                        </label>
                        <div class="relative flex items-center">
                            <span class="absolute left-3.5 text-on-surface-variant material-symbols-outlined text-[18px]">
                                lock
                            </span>
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-surface-container-low text-xs sm:text-sm text-on-surface border border-surface-container-highest focus:border-primary focus:bg-surface-container-lowest focus:outline-none transition"
                            />
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between text-xs">
                        <label class="flex items-center gap-2 cursor-pointer font-spec-data text-on-surface-variant">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="w-4 h-4 rounded text-primary focus:ring-primary border-surface-container-highest"
                            />
                            <span>Ingat sesi saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-3 px-4 rounded-xl bg-primary text-on-primary font-title-md text-xs sm:text-sm font-bold text-center shadow-md hover:bg-primary-container transition active:scale-[0.99] disabled:opacity-60 flex items-center justify-center gap-2"
                    >
                        <span v-if="form.processing" class="material-symbols-outlined text-[18px] animate-spin">
                            progress_activity
                        </span>
                        <span>{{ form.processing ? 'Memverifikasi...' : 'Masuk ke Panel Admin' }}</span>
                    </button>
                </form>

                <!-- Sample Account Pill Helper -->
                <div class="pt-3 border-t border-surface-container-highest/60 text-center space-y-2">
                    <p class="font-spec-data text-[11px] text-on-surface-variant">
                        Kredensial Default Seeder:
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-1.5">
                        <button
                            type="button"
                            @click="fillSample('admin@x-rent.test', 'password')"
                            class="px-2.5 py-1 rounded-lg bg-surface-container-low text-primary text-[11px] font-semibold border border-surface-container-highest hover:border-primary/50 transition font-spec-data"
                        >
                            admin@x-rent.test / password
                        </button>
                    </div>
                </div>
            </div>

            <!-- Back to Home Link -->
            <div class="text-center">
                <Link href="/" class="inline-flex items-center gap-1.5 text-xs text-on-surface-variant hover:text-primary transition font-title-md font-semibold">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    <span>Kembali ke Halaman Utama X-RENT</span>
                </Link>
            </div>
        </div>
    </div>
</template>
