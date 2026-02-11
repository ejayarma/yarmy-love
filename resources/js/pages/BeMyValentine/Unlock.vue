<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Lock, Heart } from 'lucide-vue-next';
import BeMyValentineController from '@/actions/App/Http/Controllers/BeMyValentineController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type Props = {
    valentine: {
        id: number;
        slug: string;
        crush_name: string;
    };
};

const props = defineProps<Props>();

const form = useForm({
    pincode: '',
});

const submit = () => {
    form.post(BeMyValentineController.unlock.url(props.valentine.slug), {
        preserveScroll: true,
    });
};
</script>

<template>

    <Head :title="`Valentine for ${valentine.crush_name}`" />

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
                    <div class="mb-6 flex justify-center animate-bounce">
                        <div class="p-6 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                            <Heart class="w-16 h-16 text-pink-200 fill-current" />
                        </div>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white drop-shadow-2xl mb-3">
                        Hey {{ valentine.crush_name }}! 💕
                    </h1>
                    <p class="text-xl text-pink-50 drop-shadow-lg">
                        Someone has a Valentine's question for you...
                    </p>
                </div>

                <!-- Unlock Card -->
                <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                    <div class="absolute inset-0 bg-white/95 backdrop-blur-xl border-2 border-white/50"></div>
                    <div class="relative p-8">
                        <div class="text-center mb-6">
                            <div class="inline-flex p-3 rounded-full bg-pink-100 mb-4">
                                <Lock class="w-8 h-8 text-pink-600" />
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Enter Your PIN</h2>
                            <p class="text-gray-600">
                                Your admirer gave you a 4-digit PIN.<br />
                                Enter it to see their message!
                            </p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid gap-2">
                                <Label for="pincode">4-Digit PIN Code</Label>
                                <Input id="pincode" v-model="form.pincode" type="text" inputmode="numeric" maxlength="4"
                                    pattern="[0-9]{4}" required placeholder="••••"
                                    class="text-center text-2xl tracking-widest" autofocus />
                                <InputError :message="form.errors.pincode" />
                            </div>

                            <Button type="submit"
                                class="w-full bg-linear-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 py-6 text-lg"
                                :disabled="form.processing">
                                <Lock class="w-5 h-5 mr-2" />
                                Unlock Message
                            </Button>
                        </form>

                        <div class="mt-6 p-4 bg-pink-50 rounded-lg">
                            <p class="text-sm text-gray-700 text-center">
                                💡 <strong>Tip:</strong> Check your messages for the PIN!
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                        <Heart class="w-4 h-4 text-pink-200 fill-current" />
                        <span class="text-sm text-white">Made with love by Yarmy Love 💕</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
