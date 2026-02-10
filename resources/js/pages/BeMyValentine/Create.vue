<script setup lang="ts">
import { Form, Head, router } from '@inertiajs/vue3';
import { Heart, Copy, Check, Sparkles, Lock } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AppLayout from '@/layouts/AppLayout.vue';

type Props = {
    generatedLink?: string;
};

const props = defineProps<Props>();

const usePincode = ref(false);
const forceYes = ref(false);
const copied = ref(false);

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
        <Head title="Be My Valentine" />

        <div class="min-h-screen relative overflow-hidden">
            <!-- Background Image with Overlay -->
            <div
                class="fixed inset-0 z-0"
                style="background-image: url('/rose-petals.png'); background-size: cover; background-position: center; background-repeat: no-repeat;"
            >
                <div class="absolute inset-0 bg-linear-to-br from-red-900/40 via-rose-800/30 to-pink-900/40"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 container mx-auto px-4 py-12 max-w-2xl">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="mb-4 flex justify-center">
                        <div class="p-4 rounded-full bg-white/10 backdrop-blur-md border border-white/20 animate-pulse">
                            <Heart class="w-12 h-12 text-pink-200 fill-current" />
                        </div>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white drop-shadow-2xl mb-3">
                        Be My Valentine
                    </h1>
                    <p class="text-lg text-pink-50 drop-shadow-lg">
                        Ask your crush in the most romantic way possible
                    </p>
                </div>

                <!-- Success Card -->
                <div v-if="generatedLink" class="mb-8">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <div class="absolute inset-0 bg-white/95 backdrop-blur-xl border-2 border-white/50"></div>
                        <div class="relative p-8">
                            <div class="text-center mb-6">
                                <div class="inline-flex p-3 rounded-full bg-pink-100 mb-4">
                                    <Check class="w-8 h-8 text-pink-600" />
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Link Created!</h2>
                                <p class="text-gray-600">Send this to your crush and wait for the magic ✨</p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <p class="text-sm text-gray-700 break-all font-mono">{{ generatedLink }}</p>
                            </div>

                            <div class="flex gap-3">
                                <Button
                                    @click="copyToClipboard"
                                    class="flex-1 bg-linear-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700"
                                >
                                    <Copy v-if="!copied" class="w-4 h-4 mr-2" />
                                    <Check v-else class="w-4 h-4 mr-2" />
                                    {{ copied ? 'Copied!' : 'Copy Link' }}
                                </Button>
                                <Button
                                    @click="createNew"
                                    variant="outline"
                                    class="flex-1"
                                >
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
                            <Form
                                method="post"
                                action="/be-my-valentine"
                                class="space-y-6"
                                v-slot="{ errors, processing }"
                            >
                                <div class="grid gap-2">
                                    <Label for="author_email">Your Email</Label>
                                    <Input
                                        id="author_email"
                                        type="email"
                                        name="author_email"
                                        required
                                        placeholder="your@email.com"
                                        autocomplete="email"
                                    />
                                    <p class="text-sm text-gray-600">We'll notify you of their response</p>
                                    <InputError :message="errors.author_email" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="author_name">Your Name</Label>
                                    <Input
                                        id="author_name"
                                        type="text"
                                        name="author_name"
                                        required
                                        placeholder="Your name"
                                    />
                                    <p class="text-sm text-gray-600">They'll see who's asking</p>
                                    <InputError :message="errors.author_name" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="crush_name">Your Crush's Name</Label>
                                    <Input
                                        id="crush_name"
                                        type="text"
                                        name="crush_name"
                                        required
                                        placeholder="Their name"
                                    />
                                    <InputError :message="errors.crush_name" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="message">Your Message</Label>
                                    <textarea
                                        id="message"
                                        name="message"
                                        rows="4"
                                        required
                                        placeholder="Write something sweet... 💕"
                                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                    ></textarea>
                                    <InputError :message="errors.message" />
                                </div>

                                <!-- Response Options -->
                                <div class="space-y-4 p-4 rounded-lg bg-pink-50">
                                    <div class="flex items-center space-x-2">
                                        <Checkbox
                                            id="force_yes"
                                            name="force_yes"
                                            v-model="forceYes"
                                            v-bind:value="'true'"

                                        />
                                        <Label for="force_yes" class="text-sm font-medium cursor-pointer">
                                            <Sparkles class="w-4 h-4 inline mr-1" />
                                            Make the "No" button elusive (they can only say yes!)
                                        </Label>
                                    </div>

                                    <p v-if="forceYes" class="text-xs text-gray-600 pl-6">
                                        The "No" button will playfully move away when they try to click it 😉
                                    </p>
                                </div>

                                <!-- Pincode Section -->
                                <div class="space-y-4 p-4 rounded-lg bg-rose-50">
                                    <div class="flex items-center space-x-2">
                                        <Checkbox
                                            id="use_pincode"
                                            name="use_pincode"
                                            v-model="usePincode"
                                            v-bind:value="'true'"
                                        />
                                        <Label for="use_pincode" class="text-sm font-medium cursor-pointer">
                                            <Lock class="w-4 h-4 inline mr-1" />
                                            Lock with a PIN code
                                        </Label>
                                    </div>

                                    <div v-if="usePincode" class="pl-6">
                                        <div class="grid gap-2">
                                            <Label for="pincode">4-Digit PIN</Label>
                                            <Input
                                                id="pincode"
                                                type="text"
                                                name="pincode"
                                                maxlength="4"
                                                pattern="[0-9]{4}"
                                                placeholder="1234"
                                                :required="usePincode"
                                            />
                                            <p class="text-xs text-gray-600">Only someone with this PIN can view your message</p>
                                            <InputError :message="errors.pincode" />
                                        </div>
                                    </div>
                                </div>

                                <Button
                                    type="submit"
                                    class="w-full bg-linear-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 py-6 text-lg"
                                    :disabled="processing"
                                >
                                    <Spinner v-if="processing" class="mr-2" />
                                    <Heart v-else class="w-5 h-5 mr-2 fill-current" />
                                    Create Valentine Link
                                </Button>
                            </Form>
                        </div>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="mt-8 text-center space-y-2">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                        <Heart class="w-4 h-4 text-pink-200 fill-current" />
                        <span class="text-sm text-white">Good luck! 💕</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
