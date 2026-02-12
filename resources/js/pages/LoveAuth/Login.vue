<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Heart, Lock, Mail } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const consentToEmail = ref(false);


const form = useForm({
    email: '',
    consent: false,
});


const submit = () => {
    form.consent = consentToEmail.value;
    form.post('/auth/send-otp');
};
</script>

<template>

    <Head title="Login to Yarmy Love" />

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="fixed inset-0 z-0"
            style="background-image: url('/rose-petals.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <div class="absolute inset-0 bg-linear-to-br from-red-900/40 via-rose-800/30 to-pink-900/40"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="mb-6 flex justify-center">
                        <div class="p-6 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                            <Heart class="w-16 h-16 text-pink-200 fill-current" />
                        </div>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white drop-shadow-2xl mb-3">
                        Welcome 💕
                    </h1>
                    <p class="text-lg text-pink-50 drop-shadow-lg">
                        Sign in to manage your Valentine requests
                    </p>
                </div>

                <!-- Login Card -->
                <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                    <div class="absolute inset-0 bg-white/95 backdrop-blur-xl border-2 border-white/50"></div>
                    <div class="relative p-8">
                        <div class="text-center mb-6">
                            <div class="inline-flex p-3 rounded-full bg-pink-100 mb-4">
                                <Mail class="w-8 h-8 text-pink-600" />
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Sign In</h2>
                            <p class="text-gray-600">
                                We'll send you a verification code
                            </p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid gap-2">
                                <Label for="email">Email Address</Label>
                                <Input id="email" v-model="form.email" type="email" required
                                    placeholder="your@email.com" class="text-lg" autofocus />
                                <!-- <p class="text-sm text-gray-600">
                                    Use the same email you used to create Valentine requests
                                </p> -->
                                <InputError :message="form.errors.email" />
                            </div>

                            <div class="grid gap-2">
                                <div class="flex gap-2">
                                    <Checkbox id="consent" class="inline" v-model="consentToEmail"
                                        v-bind:name="'true'" />
                                    <Label for="consent" class="inline leading-4.5">
                                        I agree to receiving emails from Yarmy Tech for this application. Read our
                                        <b><a href="https://yarmy.tech/privacy-policy" target="_blank">Privacy
                                                Policy</a></b>
                                    </Label>
                                </div>
                                <InputError :message="form.errors.consent" />
                            </div>


                            <Button type="submit"
                                class="w-full bg-linear-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 py-6 text-lg"
                                :disabled="form.processing">
                                <Mail class="w-5 h-5 mr-2" />
                                Send Verification Code
                            </Button>
                        </form>

                        <div class="mt-6 p-4 bg-pink-50 rounded-lg">
                            <p class="text-sm text-gray-700">
                                <strong>🔐 Secure Login:</strong> We'll send a 6-digit code to your email.
                                No passwords needed!
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="my-8 text-center">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                        <Heart class="w-4 h-4 text-pink-200 fill-current" />
                        <span class="text-sm text-white">Made with love by <a class="font-medium" target="_blank"
                                href="https://yarmy.tech">Yarmy Tech</a> 💕</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
