<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Heart, Lightbulb, Gift, User, AlertCircle } from 'lucide-vue-next';
import { ref } from 'vue';
import Love2FAController from '@/actions/App/Http/Controllers/Love2FAController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type Props = {
    love2fa: {
        id: number;
        recipient_name: string;
        gift_description: string;
        message: string;
        max_attempts: number;
        remaining_attempts: number;
        total_attempts: number;
        hints: string[];
        hints_viewed: number;
        is_revealed: boolean;
        slug: string;
    };
    attempts: Array<{
        id: number;
        guessed_name: string;
        is_correct: boolean;
        formatted_time: string;
    }>;
};

const props = defineProps<Props>();

const showHints = ref(props.love2fa.hints_viewed > 0);

const form = useForm({
    name: '',
});

const submit = () => {
    form.post(Love2FAController.guess.url(props.love2fa.slug), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const viewHints = () => {
    showHints.value = true;
    if (props.love2fa.hints_viewed === 0) {
        router.visit(Love2FAController.viewHints.url(props.love2fa.slug), {
            method: 'post',
            preserveScroll: true,
        });
        // Make a request to increment hints viewed
        // window.axios.post(route('love2fa.view-hints', props.love2fa.slug));
    }
};
</script>

<template>
    <Head title="Guess Who Sent It!" />

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Image with Overlay -->
        <div
            class="fixed inset-0 z-0"
            style="background-image: url('/rose-petals.png'); background-size: cover; background-position: center; background-repeat: no-repeat;"
        >
            <div class="absolute inset-0 bg-gradient-to-br from-red-900/40 via-rose-800/30 to-pink-900/40"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 container mx-auto px-4 py-12 max-w-3xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mb-4 flex justify-center">
                    <div class="p-4 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                        <Gift class="w-12 h-12 text-pink-200" />
                    </div>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white drop-shadow-2xl mb-3">
                    Mystery Unlocked! 🎉
                </h1>
                <p class="text-lg text-pink-50 drop-shadow-lg">
                    Now... can you guess who sent it?
                </p>
            </div>

            <!-- Gift Card -->
            <div class="relative rounded-2xl overflow-hidden shadow-2xl mb-6">
                <div class="absolute inset-0 bg-white/95 backdrop-blur-xl border-2 border-white/50"></div>
                <div class="relative p-8">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Your Mystery Gift</h2>

                        <div class="bg-gradient-to-br from-pink-50 to-red-50 rounded-xl p-6 mb-6">
                            <p class="text-3xl mb-4">🎁</p>
                            <p class="text-xl font-semibold text-gray-800 mb-2">{{ love2fa.gift_description }}</p>
                        </div>

                        <div class="bg-white border-2 border-pink-200 rounded-xl p-6">
                            <p class="text-gray-700 leading-relaxed italic">"{{ love2fa.message }}"</p>
                            <p class="text-sm text-gray-500 mt-4">- From your secret admirer 💕</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attempts Counter -->
            <div class="relative rounded-2xl overflow-hidden shadow-2xl mb-6">
                <div class="absolute inset-0 bg-white/95 backdrop-blur-xl border-2 border-white/50"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Attempts Remaining</p>
                            <p class="text-3xl font-bold" :class="love2fa.remaining_attempts <= 2 ? 'text-red-600' : 'text-gray-900'">
                                {{ love2fa.remaining_attempts }} / {{ love2fa.max_attempts }}
                            </p>
                        </div>
                        <AlertCircle v-if="love2fa.remaining_attempts <= 2" class="w-12 h-12 text-red-500" />
                    </div>
                    <div v-if="love2fa.remaining_attempts <= 2" class="mt-4 p-3 bg-red-50 rounded-lg">
                        <p class="text-sm text-red-700">
                            <strong>Warning:</strong> You're running out of attempts! Use hints wisely.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Guess Form -->
            <div class="relative rounded-2xl overflow-hidden shadow-2xl mb-6">
                <div class="absolute inset-0 bg-white/95 backdrop-blur-xl border-2 border-white/50"></div>
                <div class="relative p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">
                        Who do you think sent this? 🤔
                    </h3>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid gap-2">
                            <Label for="name">Enter their full name</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                placeholder="e.g., John Doe"
                                class="text-lg"
                                autofocus
                            />
                            <p class="text-sm text-gray-600">Type exactly how they spell their name</p>
                            <InputError :message="form.errors.name" />
                        </div>

                        <Button
                            type="submit"
                            class="w-full bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 py-6 text-lg"
                            :disabled="form.processing || love2fa.remaining_attempts === 0"
                        >
                            <User class="w-5 h-5 mr-2" />
                            Submit Guess
                        </Button>
                    </form>

                    <!-- Hints Section -->
                    <div class="mt-6">
                        <div v-if="!showHints && love2fa.hints.length > 0">
                            <Button
                                type="button"
                                @click="viewHints"
                                variant="outline"
                                class="w-full"
                            >
                                <Lightbulb class="w-4 h-4 mr-2" />
                                Need a hint? Click here
                            </Button>
                        </div>

                        <div v-else-if="love2fa.hints.length > 0" class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-6">
                            <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                <Lightbulb class="w-5 h-5 mr-2 text-yellow-600" />
                                Hints from your secret admirer:
                            </h4>
                            <ul class="space-y-2">
                                <li v-for="(hint, index) in love2fa.hints" :key="index" class="flex items-start">
                                    <span class="text-yellow-600 mr-2">💡</span>
                                    <span class="text-gray-700">{{ hint }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Previous Attempts -->
            <div v-if="attempts.length > 0" class="relative rounded-2xl overflow-hidden shadow-2xl">
                <div class="absolute inset-0 bg-white/95 backdrop-blur-xl border-2 border-white/50"></div>
                <div class="relative p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Previous Guesses</h3>
                    <div class="space-y-3">
                        <div
                            v-for="attempt in attempts"
                            :key="attempt.id"
                            class="flex items-center justify-between p-4 rounded-lg"
                            :class="attempt.is_correct ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'"
                        >
                            <div>
                                <p class="font-medium" :class="attempt.is_correct ? 'text-green-900' : 'text-red-900'">
                                    {{ attempt.guessed_name }}
                                </p>
                                <p class="text-sm text-gray-600">{{ attempt.formatted_time }}</p>
                            </div>
                            <span class="text-2xl">{{ attempt.is_correct ? '✅' : '❌' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                    <Heart class="w-4 h-4 text-pink-200 fill-current" />
                    <span class="text-sm text-white">Good luck guessing! 💕</span>
                </div>
            </div>
        </div>
    </div>
</template>
