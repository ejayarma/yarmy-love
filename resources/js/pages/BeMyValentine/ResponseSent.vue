<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Heart, Check, PartyPopper } from 'lucide-vue-next';

type Props = {
    response: 'yes' | 'no' | 'maybe';
    sender_name: string;
};

const props = defineProps<Props>();

const messages = {
    yes: {
        icon: '🎉',
        title: 'Response Sent!',
        message: `Your "Yes" has been sent to ${props.sender_name}! They're going to be so happy! 💕`,
        color: 'green',
    },
    maybe: {
        icon: '💭',
        title: 'Response Sent!',
        message: `Your response has been sent to ${props.sender_name}. Take your time to think about it!`,
        color: 'yellow',
    },
    no: {
        icon: '💌',
        title: 'Response Sent',
        message: `Your response has been sent to ${props.sender_name}. Thank you for being honest.`,
        color: 'gray',
    },
};

const currentMessage = messages[props.response];
</script>

<template>

    <Head title="Response Sent 💌" />

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="fixed inset-0 z-0"
            style="background-image: url('/rose-petals.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <div class="absolute inset-0 bg-linear-to-br from-red-900/40 via-rose-800/30 to-pink-900/40"></div>
        </div>

        <!-- Confetti for Yes -->
        <div v-if="response === 'yes'" class="fixed inset-0 pointer-events-none z-5 overflow-hidden">
            <div class="heart-float" style="left: 10%; animation-delay: 0s;">🎉</div>
            <div class="heart-float" style="left: 30%; animation-delay: 2s;">💕</div>
            <div class="heart-float" style="left: 50%; animation-delay: 4s;">✨</div>
            <div class="heart-float" style="left: 70%; animation-delay: 1s;">🎊</div>
            <div class="heart-float" style="left: 90%; animation-delay: 3s;">💖</div>
        </div>

        <!-- Content -->
        <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-lg">
                <!-- Main Card -->
                <div
                    class="relative rounded-2xl overflow-hidden shadow-2xl animate-in fade-in slide-in-from-bottom-4 duration-1000">
                    <div class="absolute inset-0 bg-white/95 backdrop-blur-xl border-2 border-white/50"></div>
                    <div class="relative p-12">
                        <div class="text-center space-y-6">
                            <!-- Icon -->
                            <div class="flex justify-center">
                                <div class="p-6 rounded-full" :class="{
                                    'bg-green-100': response === 'yes',
                                    'bg-yellow-100': response === 'maybe',
                                    'bg-gray-100': response === 'no'
                                }">
                                    <PartyPopper v-if="response === 'yes'" class="w-16 h-16 text-green-600" />
                                    <Heart v-else class="w-16 h-16" :class="{
                                        'text-yellow-600': response === 'maybe',
                                        'text-gray-600': response === 'no'
                                    }" />
                                </div>
                            </div>

                            <!-- Emoji -->
                            <p class="text-6xl">{{ currentMessage.icon }}</p>

                            <!-- Title -->
                            <h1 class="text-3xl font-bold text-gray-900">
                                {{ currentMessage.title }}
                            </h1>

                            <!-- Message -->
                            <p class="text-lg text-gray-600">
                                {{ currentMessage.message }}
                            </p>

                            <!-- Success indicator -->
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full" :class="{
                                'bg-green-100 text-green-700': response === 'yes',
                                'bg-yellow-100 text-yellow-700': response === 'maybe',
                                'bg-gray-100 text-gray-700': response === 'no'
                            }">
                                <Check class="w-4 h-4" />
                                <span class="text-sm font-medium">{{ sender_name }} has been notified</span>
                            </div>

                            <!-- Additional message for Yes -->
                            <div v-if="response === 'yes'"
                                class="bg-pink-50 border-2 border-pink-200 rounded-xl p-6 mt-8">
                                <p class="text-sm text-pink-800">
                                    💡 <strong>What's next?</strong><br />
                                    {{ sender_name }} will probably reach out soon! Get ready for a special Valentine's
                                    Day! 🎊
                                </p>
                            </div>
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
