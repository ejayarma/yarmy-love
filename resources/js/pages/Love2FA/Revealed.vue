<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Heart, Gift, Sparkles, PartyPopper } from 'lucide-vue-next';

type Props = {
    love2fa: {
        id: number;
        sender_name: string;
        recipient_name: string;
        gift_description: string;
        message: string;
        max_attempts: number;
        total_attempts: number;
        hints: string[];
        is_revealed: boolean;
        revealed_at: string;
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

</script>

<template>
    <Head title="Mystery Revealed! 🎉" />

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Image with Overlay -->
        <div
            class="fixed inset-0 z-0"
            style="background-image: url('/rose-petals.png'); background-size: cover; background-position: center; background-repeat: no-repeat;"
        >
            <div class="absolute inset-0 bg-linear-to-br from-red-900/40 via-rose-800/30 to-pink-900/40"></div>
        </div>

        <!-- Confetti Effect -->
        <div class="fixed inset-0 pointer-events-none z-5 overflow-hidden">
            <div class="heart-float" style="left: 10%; animation-delay: 0s;">🎉</div>
            <div class="heart-float" style="left: 30%; animation-delay: 2s;">💕</div>
            <div class="heart-float" style="left: 50%; animation-delay: 4s;">✨</div>
            <div class="heart-float" style="left: 70%; animation-delay: 1s;">🎊</div>
            <div class="heart-float" style="left: 90%; animation-delay: 3s;">💖</div>
        </div>

        <!-- Content -->
        <div class="relative z-10 container mx-auto px-4 py-12 max-w-3xl">
            <!-- Celebration Header -->
            <div class="text-center mb-8 animate-in fade-in duration-1000">
                <div class="mb-6 flex justify-center">
                    <div class="p-6 rounded-full bg-white/10 backdrop-blur-md border border-white/20 animate-bounce">
                        <Sparkles class="w-16 h-16 text-yellow-200" />
                    </div>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold text-white drop-shadow-2xl mb-4">
                    Mystery Solved! 🎉
                </h1>
                <p class="text-2xl text-pink-50 drop-shadow-lg">
                    You guessed correctly!
                </p>
            </div>

            <!-- Reveal Card -->
            <div class="relative rounded-2xl overflow-hidden shadow-2xl mb-6 animate-in fade-in slide-in-from-bottom-4 duration-1000">
                <div class="absolute inset-0 bg-linear-to-br from-white/95 to-pink-50/95 backdrop-blur-xl border-2 border-white/50"></div>
                <div class="relative p-8">
                    <div class="text-center mb-8">
                        <div class="inline-flex p-4 rounded-full bg-linear-to-br from-red-100 to-pink-100 mb-6">
                            <Heart class="w-16 h-16 text-red-500 fill-current animate-pulse" />
                        </div>

                        <h2 class="text-3xl font-bold text-gray-900 mb-4">
                            Your Secret Admirer Is...
                        </h2>

                        <div class="bg-linear-to-br from-red-500 to-pink-500 text-white rounded-2xl p-8 mb-6">
                            <p class="text-5xl font-bold mb-2">{{ love2fa.sender_name }}</p>
                            <p class="text-xl opacity-90">💕</p>
                        </div>

                        <p class="text-lg text-gray-600 mb-8">
                            It took you {{ love2fa.total_attempts }} {{ love2fa.total_attempts === 1 ? 'attempt' : 'attempts' }} to figure it out!
                        </p>
                    </div>

                    <!-- Gift Details -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-xl p-6 border-2 border-pink-200">
                            <div class="flex items-center gap-3 mb-4">
                                <Gift class="w-6 h-6 text-pink-600" />
                                <h3 class="text-lg font-semibold text-gray-900">The Gift</h3>
                            </div>
                            <p class="text-gray-700 text-lg">{{ love2fa.gift_description }}</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 border-2 border-pink-200">
                            <div class="flex items-center gap-3 mb-4">
                                <Heart class="w-6 h-6 text-pink-600 fill-current" />
                                <h3 class="text-lg font-semibold text-gray-900">The Message</h3>
                            </div>
                            <p class="text-gray-700 leading-relaxed italic">"{{ love2fa.message }}"</p>
                            <p class="text-sm text-gray-500 mt-4 text-right">- {{ love2fa.sender_name }}</p>
                        </div>
                    </div>

                    <!-- Call to Action -->
                    <div class="mt-8 p-6 bg-linear-to-br from-pink-50 to-red-50 rounded-xl border-2 border-pink-200">
                        <p class="text-center text-gray-700 mb-4">
                            <strong>{{ love2fa.sender_name }}</strong> has been waiting for you to figure it out! 💕
                        </p>
                        <p class="text-center text-gray-600">
                            Now's the perfect time to reach out and say thank you! 😊
                        </p>
                    </div>
                </div>
            </div>

            <!-- Your Journey -->
            <div class="relative rounded-2xl overflow-hidden shadow-2xl mb-6">
                <div class="absolute inset-0 bg-white/95 backdrop-blur-xl border-2 border-white/50"></div>
                <div class="relative p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Your Guessing Journey</h3>

                    <div class="space-y-4">
                        <div
                            v-for="(attempt, index) in attempts"
                            :key="attempt.id"
                            class="flex items-center gap-4 p-4 rounded-lg"
                            :class="attempt.is_correct ? 'bg-green-50 border-2 border-green-300' : 'bg-gray-50 border border-gray-200'"
                        >
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-white flex items-center justify-center font-bold text-gray-700 border-2"
                                :class="attempt.is_correct ? 'border-green-500' : 'border-gray-300'"
                            >
                                {{ attempts.length - index }}
                            </div>
                            <div class="flex-1">
                                <p class="font-medium" :class="attempt.is_correct ? 'text-green-900' : 'text-gray-900'">
                                    {{ attempt.guessed_name }}
                                </p>
                                <p class="text-sm text-gray-600">{{ attempt.formatted_time }}</p>
                            </div>
                            <span class="text-3xl">{{ attempt.is_correct ? '🎉' : '❌' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <div class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                    <PartyPopper class="w-5 h-5 text-yellow-200" />
                    <span class="text-white">Congratulations! The mystery is solved! 🎊</span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes float-up {
    0% {
        transform: translateY(100vh) scale(0);
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        transform: translateY(-100vh) scale(1.5);
        opacity: 0;
    }
}

.heart-float {
    position: absolute;
    bottom: -50px;
    font-size: 2rem;
    animation: float-up 15s infinite ease-in;
    opacity: 0.8;
}
</style>
