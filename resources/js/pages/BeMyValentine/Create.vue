<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Heart, Copy, Check, Sparkles, Lock } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';

type Props = {
    generatedLink?: string;
    pincode?: string;
    senderEmail?: string;
};

const props = defineProps<Props>();

onMounted(() => {
    document.title = 'Be My Valentine 💕';
    console.log('Sender Email:', props.senderEmail);
});

const copied = ref(false);
const usePincode = ref(false);
const forceYes = ref(false);

const form = useForm({
    sender_name: '',
    sender_email: props.senderEmail || '',
    crush_name: '',
    message: '',
    force_yes: false,
    use_pincode: false,
    pincode: '',
});

const submit = () => {
    form.force_yes = forceYes.value;
    form.use_pincode = usePincode.value;
    form.post('/be-my-valentine');
};

const copyToClipboard = async () => {
    if (props.generatedLink) {
        await navigator.clipboard.writeText(props.generatedLink);
        copied.value = true;
        setTimeout(() => {
            copied.value = false;
        }, 2000);
    }
};

const createNew = () => {
    router.visit('/be-my-valentine');
};
</script>

<template>
    <AppLayout>

        <Head title="Be My Valentine 💕" />

        <div class="min-h-screen relative overflow-hidden">
            <!-- Background Image with Overlay -->
            <div class="fixed inset-0 z-0"
                style="background-image: url('/rose-petals.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                <div class="absolute inset-0 bg-linear-to-br from-red-900/40 via-rose-800/30 to-pink-900/40"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 container mx-auto px-4 py-12 max-w-2xl">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="mb-4 flex justify-center">
                        <div class="p-4 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                            <Heart class="w-12 h-12 text-pink-200 fill-current" />
                        </div>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white drop-shadow-2xl mb-3">
                        Be My Valentine
                    </h1>
                    <p class="text-lg text-pink-50 drop-shadow-lg">
                        Ask your crush to be your Valentine in a special way
                    </p>
                </div>

                <!-- Success Card -->
                <div v-if="generatedLink" class="mb-8">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <div class="absolute inset-0 bg-white/95 backdrop-blur-xl border-2 border-white/50"></div>
                        <div class="relative p-8">
                            <div class="text-center mb-6">
                                <div class="inline-flex p-3 rounded-full bg-green-100 mb-4">
                                    <Check class="w-8 h-8 text-green-600" />
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Request Created! 🎉</h2>
                                <p class="text-gray-600">Share this with your crush</p>
                            </div>

                            <div class="space-y-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Valentine Link</label>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-sm text-gray-700 break-all font-mono">{{ generatedLink }}</p>
                                    </div>
                                </div>

                                <div v-if="pincode">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">PIN Code</label>
                                    <div class="bg-pink-50 rounded-lg p-4 border-2 border-pink-200">
                                        <p class="text-2xl font-bold text-center text-pink-600 tracking-widest">{{
                                            pincode }}</p>
                                        <p class="text-xs text-center text-gray-600 mt-2">Share this PIN with your crush
                                            to unlock the message</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <Button @click="copyToClipboard"
                                    class="flex-1 bg-linear-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700">
                                    <Copy v-if="!copied" class="w-4 h-4 mr-2" />
                                    <Check v-else class="w-4 h-4 mr-2" />
                                    {{ copied ? 'Copied!' : 'Copy Link' }}
                                </Button>
                                <Button @click="createNew" variant="outline" class="flex-1">
                                    Create Another
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Card -->
                <div v-else>
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <div class="absolute inset-0 bg-white/95 backdrop-blur-xl border-2 border-white/50"></div>
                        <div class="relative p-8">
                            <form @submit.prevent="submit" class="space-y-6">
                                <!-- Your Info -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Your Information</h3>

                                    <div class="grid gap-2">
                                        <Label for="sender_name">Your Name <span class="text-red-500">*</span></Label>
                                        <Input id="sender_name" v-model="form.sender_name" type="text" required
                                            placeholder="Your name" />
                                        <InputError :message="form.errors.sender_name" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="sender_email">Your Email <span class="text-red-500">*</span></Label>
                                        <Input id="sender_email" disabled v-model="form.sender_email" type="email"
                                            required placeholder="your@email.com" />
                                        <p class="text-sm text-gray-600">We'll notify you when they respond</p>
                                        <InputError :message="form.errors.sender_email" />
                                    </div>
                                </div>

                                <!-- Crush Info -->
                                <div class="space-y-4 pt-4 border-t">
                                    <h3 class="text-lg font-semibold text-gray-900">Your Crush</h3>

                                    <div class="grid gap-2">
                                        <Label for="crush_name">Their Name <span class="text-red-500">*</span></Label>
                                        <Input id="crush_name" v-model="form.crush_name" type="text" required
                                            placeholder="Their name" />
                                        <InputError :message="form.errors.crush_name" />
                                    </div>
                                </div>

                                <!-- Message -->
                                <div class="space-y-4 pt-4 border-t">
                                    <div class="grid gap-2">
                                        <Label for="message">Your Message <span class="text-red-500">*</span></Label>
                                        <textarea id="message" v-model="form.message" rows="4" required
                                            placeholder="Write a heartfelt message to your crush..."
                                            class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"></textarea>
                                        <InputError :message="form.errors.message" />
                                    </div>
                                </div>

                                <!-- Options -->
                                <div class="space-y-4 pt-4 border-t">
                                    <h3 class="text-lg font-semibold text-gray-900">Options</h3>

                                    <div class="space-y-3">
                                        <!-- Force Yes -->
                                        <div class="flex items-center space-x-2 p-3 rounded-lg bg-pink-50">
                                            <Checkbox id="force_yes" v-model:checked="forceYes" />
                                            <Label for="force_yes" class="text-sm font-medium cursor-pointer flex-1">
                                                <Sparkles class="w-4 h-4 inline mr-1" />
                                                Make the "No" button run away (playful mode)
                                            </Label>
                                        </div>

                                        <!-- Pincode -->
                                        <div class="space-y-3 p-3 rounded-lg bg-pink-50">
                                            <div class="flex items-center space-x-2">
                                                <Checkbox id="use_pincode" v-model="usePincode" v-bind:name="'true'" />
                                                <Label for="use_pincode" class="text-sm font-medium cursor-pointer">
                                                    <Lock class="w-4 h-4 inline mr-1" />
                                                    Secure with a PIN code
                                                </Label>
                                            </div>

                                            <div v-if="usePincode" class="pl-6">
                                                <div class="grid gap-2">
                                                    <Label for="pincode">4-Digit PIN</Label>
                                                    <Input id="pincode" v-model="form.pincode" type="text" maxlength="4"
                                                        pattern="[0-9]{4}" placeholder="1234" :required="usePincode" />
                                                    <p class="text-xs text-gray-600">They'll need this PIN to view your
                                                        message</p>
                                                    <InputError :message="form.errors.pincode" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <Button type="submit"
                                    class="w-full bg-linear-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 py-6 text-lg"
                                    :disabled="form.processing">
                                    <Heart class="w-5 h-5 mr-2 fill-current" />
                                    Create Valentine Request
                                </Button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="mt-8 text-center">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                        <Heart class="w-4 h-4 text-pink-200 fill-current" />
                        <span class="text-sm text-white">Take a chance on love 💕</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
