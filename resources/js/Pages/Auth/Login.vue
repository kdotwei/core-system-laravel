<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

// 新增的方法，用於處理 Google 登錄按鈕點擊事件
const signInWithGoogle = () => {
    window.location.href = route('auth.google');
};
</script>

<template>
    <Head title="Log in" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    Forgot your password?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form>
        <!-- 新增的 Google 登錄按鈕 -->
        <div class="mt-6">
            <button @click="signInWithGoogle" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                    <path fill="#FFC107" d="M43.6 20H24v8.5h11.5c-0.5 3-2.1 5.5-4.6 7.2v5.9h7.3c4.3-3.9 6.8-9.7 6.8-16.1C43.6 21.6 43.6 20 43.6 20z"/>
                    <path fill="#FF3D00" d="M24 44c5.9 0 10.9-1.9 14.6-5.1l-7.3-5.9c-2.1 1.4-4.8 2.2-7.3 2.2-5.6 0-10.4-3.8-12.1-8.9H4.8v5.6C8.5 39.1 16.1 44 24 44z"/>
                    <path fill="#4CAF50" d="M11.9 26.3c-0.5-1.5-0.8-3.1-0.8-4.7s0.3-3.2 0.8-4.7v-5.6H4.8a23.95 23.95 0 0 1 0 16.1l7.1-0.1z"/>
                    <path fill="#1976D2" d="M24 8c6.5 0 12.2 2.3 16.6 6.3l12.4-12.4C36.9 3.7 30.2 2 24 2 16.1 2 8.5 6.9 4.8 14.4l7.1 5.6C13.6 12.8 18.4 8 24 8z"/>
                </svg>
                Sign in with Google
            </button>
        </div>
    </AuthenticationCard>
</template>
