<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Heart, HeartCrack, MessageCircle } from 'lucide-vue-next';
import { ref } from 'vue';
import BeMyValentineController from '@/actions/App/Http/Controllers/BeMyValentineController';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';

type Props = {
    valentine: {
        id: number;
        slug: string;
        sender_name: string;
        crush_name: string;
        message: string;
        force_yes: boolean;
    };
};

const props = defineProps<Props>();

const showMessageBox = ref(false);
const noPresses = ref(0);

const form = useForm({
    response: 'yes' as 'yes' | 'no' | 'maybe',
    message: '',
});

const respond = (response: 'yes' | 'no' | 'maybe') => {
    form.response = response;

    if (response === 'yes') {
        showMessageBox.value = true;
    } else {
        if (props.valentine.force_yes) {
            noPresses.value++;
            return; // Don't actually submit when force_yes
        }
        form.post(BeMyValentineController.respond.url(props.valentine.slug), {
            preserveScroll: true,
        });
    }
};

const submitWithMessage = () => {
    form.post(BeMyValentineController.respond.url(props.valentine.slug));
};

const dodgeNo = (e: MouseEvent) => {
    if (props.valentine.force_yes) {
        const btn = e.target as HTMLElement;
        const randomX = Math.random() * 200 - 100;
        const randomY = Math.random() * 150 - 75;
        btn.style.transform = `translate(${randomX}px, ${randomY}px)`;
        btn.style.transition = 'transform 0.3s ease';
    }
};

// Grows the Yes button slightly each time No is pressed
const yesFontSize = (base: number = 18) => {
    return `${base + noPresses.value * 4}px`;
};

const yesPadding = () => {
    const extra = noPresses.value * 4;
    return `${24 + extra}px ${32 + extra}px`;
};
</script>

<template>
    <Head :title="`${valentine.crush_name}, will you be my Valentine?`" />

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="fixed inset-0 z-0"
            style="background-image: url('/rose-petals.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <div class="absolute inset-0 bg-linear-to-br from-red-900/40 via-rose-800/30 to-pink-900/40"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-2xl">
                <!-- Main Card -->
                <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                    <div class="absolute inset-0 bg-white/95 backdrop-blur-xl border-2 border-white/50"></div>
                    <div class="relative p-8 md:p-12">
                        <!-- Header -->
                        <div class="text-center mb-8">
                            <div class="mb-6 flex justify-center">
                                <div class="p-4 rounded-full bg-linear-to-br from-red-100 to-pink-100">
                                    <Heart class="w-16 h-16 text-red-500 fill-current animate-pulse" />
                                </div>
                            </div>

                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                                {{ valentine.crush_name }},<br />
                                Will you be my Valentine? 💕
                            </h1>

                            <p class="text-lg text-gray-600">
                                From <strong class="text-red-600">{{ valentine.sender_name }}</strong>
                            </p>
                        </div>

                        <!-- Message -->
                        <div class="bg-linear-to-br from-pink-50 to-red-50 rounded-xl p-6 mb-8 border-2 border-pink-200">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line text-center italic">
                                "{{ valentine.message }}"
                            </p>
                        </div>

                        <!-- Response Buttons -->
                        <div v-if="!showMessageBox" class="space-y-4">
                            <p class="text-center text-gray-600 mb-6">How do you feel?</p>

                            <!-- Button row with overflow visible so No can dodge outside -->
                            <div class="flex items-center justify-center gap-4" style="overflow: visible; min-height: 80px;">
                                <!-- Yes Button - grows each time No is pressed -->
                                <button
                                    @click="respond('yes')"
                                    :disabled="form.processing"
                                    :style="{
                                        fontSize: yesFontSize(),
                                        padding: yesPadding(),
                                        transition: 'all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)',
                                    }"
                                    class="flex items-center gap-2 rounded-xl font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 shadow-lg disabled:opacity-50"
                                >
                                    Yes!
                                    <Heart class="fill-current shrink-0" :style="{ width: `${20 + noPresses * 4}px`, height: `${20 + noPresses * 4}px` }" />
                                </button>

                                <!-- No Button - dodges on hover and click -->
                                <button
                                    @click="respond('no')"
                                    @mouseenter="dodgeNo"
                                    :disabled="form.processing"
                                    style="transition: transform 0.3s ease;"
                                    class="flex items-center gap-2 px-6 py-6 rounded-xl font-semibold text-lg border-2 border-gray-300 bg-white hover:bg-gray-50 text-gray-700 shadow-sm disabled:opacity-50 shrink-0"
                                >
                                    No
                                    <HeartCrack class="w-5 h-5 shrink-0" />
                                </button>
                            </div>

                            <p v-if="valentine.force_yes" class="text-xs text-center text-gray-500 mt-4">
                                💡 Tip: The "No" button is a bit shy...
                            </p>
                        </div>

                        <!-- Optional Message Box (shown after clicking Yes) -->
                        <div v-else class="space-y-4">
                            <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6 text-center mb-6">
                                <p class="text-2xl mb-2">🎉</p>
                                <p class="text-green-800 font-semibold">Yay! That's wonderful!</p>
                                <p class="text-sm text-green-700 mt-2">Want to add a message for {{ valentine.sender_name }}?</p>
                            </div>

                            <div class="grid gap-2">
                                <Label for="message">Your Message (Optional)</Label>
                                <Textarea
                                    id="message"
                                    v-model="form.message"
                                    :rows="4"
                                    placeholder="Write something sweet back..."
                                    class="resize-none"
                                />
                            </div>

                            <div class="flex gap-3">
                                <Button
                                    @click="submitWithMessage"
                                    class="flex-1 bg-linear-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 py-6 text-lg"
                                    :disabled="form.processing"
                                >
                                    <MessageCircle class="w-5 h-5 mr-2" />
                                    Send Response
                                </Button>
                                <Button @click="showMessageBox = false" variant="outline" :disabled="form.processing">
                                    Back
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                        <Heart class="w-4 h-4 text-pink-200 fill-current" />
                        <span class="text-sm text-white">Made with love by Yarmy Love 💕</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
