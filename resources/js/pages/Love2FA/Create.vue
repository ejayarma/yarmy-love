<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Heart, Lock, Copy, Check, Gift, Sparkles, Plus, X } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';

type Props = {
    generatedLink?: string;
    recipientPincode?: string;
    senderEmail?: string;
};

const props = defineProps<Props>();

const copied = ref(false);
const hints = ref<string[]>(['']);

const form = useForm({
    sender_name: '',
    sender_email: props.senderEmail || '',
    recipient_name: '',
    recipient_pincode: '',
    gift_description: '',
    message: '',
    max_attempts: 5,
    hints: [] as string[],
});

const addHint = () => {
    if (hints.value.length < 5) {
        hints.value.push('');
    }
};

const removeHint = (index: number) => {
    hints.value.splice(index, 1);
};

const submit = () => {
    // Filter out empty hints
    form.hints = hints.value.filter(h => h.trim() !== '');
    form.post('/love-2fa');
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
    router.visit('/love-2fa');
};
</script>

<template>
    <AppLayout>

        <Head title="Love 2FA - Create Mystery Gift" />

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
                            <Gift class="w-12 h-12 text-pink-200" />
                        </div>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white drop-shadow-2xl mb-3">
                        Love 2FA
                    </h1>
                    <p class="text-lg text-pink-50 drop-shadow-lg">
                        Send an anonymous gift - let them guess who you are!
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
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Mystery Created! 🎉</h2>
                                <p class="text-gray-600">Share these with your recipient</p>
                            </div>

                            <div class="space-y-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mystery Link</label>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-sm text-gray-700 break-all font-mono">{{ generatedLink }}</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Recipient's PIN
                                        Code</label>
                                    <div class="bg-pink-50 rounded-lg p-4 border-2 border-pink-200">
                                        <p class="text-2xl font-bold text-center text-pink-600 tracking-widest">{{
                                            recipientPincode }}</p>
                                        <p class="text-xs text-center text-gray-600 mt-2">They need this to unlock the
                                            mystery</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                                <div class="flex">
                                    <Sparkles class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0" />
                                    <div>
                                        <p class="text-sm text-yellow-800">
                                            <strong>Important:</strong> Share BOTH the link and PIN code with your
                                            recipient.
                                            They'll need the PIN to unlock the mystery, then they have to guess YOUR
                                            NAME!
                                        </p>
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
                                <!-- Sender Info -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Your Information</h3>

                                    <div class="grid gap-2">
                                        <Label for="sender_name">Your Name <span class="text-red-500">*</span></Label>
                                        <Input id="sender_name" v-model="form.sender_name" type="text" required
                                            placeholder="Your full name" />
                                        <p class="text-sm text-gray-600">This is what they need to guess</p>
                                        <InputError :message="form.errors.sender_name" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="sender_email">Your Email <span class="text-red-500">*</span></Label>
                                        <Input disabled id="sender_email" v-model="form.sender_email" type="email" required
                                            placeholder="your@email.com" />
                                        <p class="text-sm text-gray-600">We'll notify you when they make guesses</p>
                                        <InputError :message="form.errors.sender_email" />
                                    </div>
                                </div>

                                <!-- Recipient Info -->
                                <div class="space-y-4 pt-4 border-t">
                                    <h3 class="text-lg font-semibold text-gray-900">Recipient Information</h3>

                                    <div class="grid gap-2">
                                        <Label for="recipient_name">Recipient's Name <span
                                                class="text-red-500">*</span></Label>
                                        <Input id="recipient_name" v-model="form.recipient_name" type="text" required
                                            placeholder="Their name" />
                                        <InputError :message="form.errors.recipient_name" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="recipient_pincode">Create a 4-Digit PIN <span
                                                class="text-red-500">*</span></Label>
                                        <Input id="recipient_pincode" v-model="form.recipient_pincode" type="text"
                                            maxlength="4" pattern="[0-9]{4}" required placeholder="1234" />
                                        <p class="text-sm text-gray-600">
                                            <Lock class="w-3 h-3 inline mr-1" />
                                            They'll need this PIN to unlock and see the mystery
                                        </p>
                                        <InputError :message="form.errors.recipient_pincode" />
                                    </div>
                                </div>

                                <!-- Gift Details -->
                                <div class="space-y-4 pt-4 border-t">
                                    <h3 class="text-lg font-semibold text-gray-900">Gift Details</h3>

                                    <div class="grid gap-2">
                                        <Label for="gift_description">Gift Description <span
                                                class="text-red-500">*</span></Label>
                                        <Input id="gift_description" v-model="form.gift_description" type="text"
                                            required placeholder="e.g., A dozen red roses, A handwritten poem, etc." />
                                        <InputError :message="form.errors.gift_description" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="message">Your Message <span class="text-red-500">*</span></Label>
                                        <textarea id="message" v-model="form.message" rows="4" required
                                            placeholder="Write a sweet message for your mystery gift..."
                                            class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"></textarea>
                                        <InputError :message="form.errors.message" />
                                    </div>
                                </div>

                                <!-- Hints Section -->
                                <div class="space-y-4 pt-4 border-t">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-gray-900">Hints (Optional)</h3>
                                        <Button type="button" @click="addHint" variant="outline" size="sm"
                                            :disabled="hints.length >= 5">
                                            <Plus class="w-4 h-4 mr-1" />
                                            Add Hint
                                        </Button>
                                    </div>

                                    <div v-for="(hint, index) in hints" :key="index" class="flex gap-2">
                                        <Input v-model="hints[index]" type="text" :placeholder="`Hint ${index + 1}`"
                                            class="flex-1" />
                                        <Button type="button" @click="removeHint(index)" variant="ghost" size="icon">
                                            <X class="w-4 h-4" />
                                        </Button>
                                    </div>
                                    <p class="text-sm text-gray-600">Give them clues about who you are (max 5 hints)</p>
                                </div>

                                <!-- Settings -->
                                <div class="space-y-4 pt-4 border-t">
                                    <div class="grid gap-2">
                                        <Label for="max_attempts">Maximum Guessing Attempts</Label>
                                        <select id="max_attempts" v-model="form.max_attempts"
                                            class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                                            <option :value="3">3 attempts</option>
                                            <option :value="5">5 attempts</option>
                                            <option :value="7">7 attempts</option>
                                            <option :value="10">10 attempts</option>
                                        </select>
                                        <InputError :message="form.errors.max_attempts" />
                                    </div>
                                </div>

                                <Button type="submit"
                                    class="w-full bg-linear-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 py-6 text-lg"
                                    :disabled="form.processing">
                                    <Gift class="w-5 h-5 mr-2" />
                                    Create Mystery Gift
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
                        <span class="text-sm text-white">Your secret is safe with us 💕</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
