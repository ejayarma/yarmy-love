<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { Lock, Mail, RefreshCw } from 'lucide-vue-next';
import { ref, onMounted } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type Props = {
    email: string;
};

const props = defineProps<Props>();

const form = useForm({
    email: props.email,
    otp: '',
});

const resendForm = useForm({
    email: props.email,
});

const canResend = ref(false);
const countdown = ref(60);

const submit = () => {
    form.post('/auth/verify-otp');
};

const resendOtp = () => {
    resendForm.post('/auth/resend-otp', {
        preserveScroll: true,
        onSuccess: () => {
            canResend.value = false;
            countdown.value = 60;
            startCountdown();
        },
    });
};

const startCountdown = () => {
    const timer = setInterval(() => {
        countdown.value--;
        if (countdown.value <= 0) {
            clearInterval(timer);
            canResend.value = true;
        }
    }, 1000);
};

const changeEmail = () => {
    router.visit('/login');
};

onMounted(() => {
    startCountdown();
});
</script>

<template>
    <Head title="Enter Verification Code" />

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Image with Overlay -->
        <div
            class="fixed inset-0 z-0"
            style="background-image: url('/rose-petals.png'); background-size: cover; background-position: center; background-repeat: no-repeat;"
        >
            <div class="absolute inset-0 bg-linear-to-br from-red-900/40 via-rose-800/30 to-pink-900/40"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="mb-6 flex justify-center">
                        <div class="p-6 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                            <Mail class="w-16 h-16 text-pink-200" />
                        </div>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white drop-shadow-2xl mb-3">
                        Check Your Email
                    </h1>
                    <p class="text-lg text-pink-50 drop-shadow-lg">
                        We sent a code to <span class="font-medium">{{ email }}</span>
                    </p>
                </div>

                <!-- Verify Card -->
                <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                    <div class="absolute inset-0 bg-white/95 backdrop-blur-xl border-2 border-white/50"></div>
                    <div class="relative p-8">
                        <div class="text-center mb-6">
                            <div class="inline-flex p-3 rounded-full bg-pink-100 mb-4">
                                <Lock class="w-8 h-8 text-pink-600" />
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Enter Verification Code</h2>
                            <p class="text-gray-600">
                                Enter the 6-digit code from your email
                            </p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid gap-2">
                                <Label for="otp">Verification Code</Label>
                                <Input
                                    id="otp"
                                    v-model="form.otp"
                                    type="text"
                                    inputmode="numeric"
                                    maxlength="6"
                                    pattern="[0-9]{6}"
                                    required
                                    placeholder="000000"
                                    class="text-center text-3xl tracking-widest font-mono"
                                    autofocus
                                />
                                <InputError :message="form.errors.otp" />
                            </div>

                            <Button
                                type="submit"
                                class="w-full bg-linear-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 py-6 text-lg"
                                :disabled="form.processing || form.otp.length !== 6"
                            >
                                <Lock class="w-5 h-5 mr-2" />
                                Verify & Sign In
                            </Button>
                        </form>

                        <!-- Resend Section -->
                        <div class="mt-6 text-center space-y-3">
                            <p class="text-sm text-gray-600">Didn't receive the code?</p>

                            <Button
                                v-if="canResend"
                                type="button"
                                @click="resendOtp"
                                variant="outline"
                                class="w-full"
                                :disabled="resendForm.processing"
                            >
                                <RefreshCw class="w-4 h-4 mr-2" />
                                Resend Code
                            </Button>

                            <p v-else class="text-sm text-gray-500">
                                Resend available in {{ countdown }}s
                            </p>

                            <Button
                                type="button"
                                @click="changeEmail"
                                variant="ghost"
                                class="w-full text-sm"
                            >
                                Use a different email
                            </Button>
                        </div>

                        <div class="mt-6 p-4 bg-primary/5 rounded-lg border-2 border-primary/20">
                            <p class="text-sm text-gray-700">
                                <strong>💡 Tip:</strong> Check your spam folder if you don't see the email.
                                The code expires in 10 minutes.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="my-8 text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                        <Lock class="w-4 h-4 text-pink-200" />
                        <span class="text-sm text-white">Secure authentication 🔐</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
