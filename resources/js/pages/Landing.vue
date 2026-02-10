<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Gift, Heart, Lock, Sparkles } from 'lucide-vue-next';
import BeMine from '@/components/icons/BeMine.vue';
import GiftBox from '@/components/icons/GiftBox.vue';
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';

const features = [
    {
        title: 'Love 2FA',
        description: 'Send anonymous gifts and let your lover guess who the secret admirer is. Keep the mystery alive!',
        icon: 'lock',
        gradient: 'from-red-500 to-pink-500',
        link: '/love-2fa'
    },
    {
        title: 'Be My Valentine',
        description: 'Ask your crush to be your Valentine in the most romantic way possible. Make this day unforgettable!',
        icon: 'heart',
        gradient: 'from-pink-500 to-rose-500',
        link: '/be-my-valentine'
    }
];

const navigateTo = (url: string) => {
    console.log(`Navigating to ${url}`);
    router.visit(url);
};


</script>

<template>
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Image with Overlay -->
        <div
            class="fixed inset-0 z-0"
            style="background-image: url('/rose-petals.png'); background-size: cover; background-position: center; background-repeat: no-repeat;"
        >
            <div class="absolute inset-0 bg-gradient-to-br from-red-900/40 via-rose-800/30 to-pink-900/40 backdrop-blur-[2px]"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 container mx-auto px-4 py-12 md:py-20">
            <!-- Hero Section -->
            <div class="text-center mb-16 space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-1000">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-4">
                    <Sparkles class="w-4 h-4 text-pink-200" />
                    <span class="text-sm text-white font-medium">Valentine's Day 2026</span>
                </div>

                <h1 class="text-5xl md:text-7xl font-bold text-white drop-shadow-2xl">
                    Yarmy Love
                </h1>

                <p class="text-xl md:text-2xl text-pink-50 max-w-2xl mx-auto drop-shadow-lg">
                    Spreading love and romance this Valentine's Day
                </p>
            </div>

            <!-- Feature Cards -->
            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto mb-12">
                <!-- Love 2FA Card -->
                <Card class="group hover:scale-105 transition-all duration-500 bg-white/95 backdrop-blur-xl border-2 border-white/50 shadow-2xl hover:shadow-pink-500/20 overflow-hidden flex flex-col">
                    <CardHeader class="px-8 pt-10 pb-4 text-center relative flex-grow">
                        <!-- SVG Illustration -->
                        <div class="mb-6 flex justify-center">
                            <div class="relative">
                                <GiftBox />
                            </div>
                        </div>

                        <CardTitle class="text-2xl font-bold text-gray-900">Love 2FA</CardTitle>
                        <CardDescription class="text-base text-gray-600 mt-2 min-h-[3rem]">
                            Send anonymous gifts and let your lover guess who the secret admirer is.
                            <span class="hidden xl:inline">Keep the mystery alive!</span>
                        </CardDescription>
                    </CardHeader>

                    <CardContent class="px-8 pb-3">
                        <Button
                            class="w-full bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white font-semibold py-6 text-lg shadow-lg hover:shadow-xl transition-all"
                            @click="() => navigateTo('/love-2fa')"
                        >
                            <Lock class="w-5 h-5 mr-2" />
                            Create Mystery Gift
                        </Button>
                    </CardContent>
                </Card>

                <!-- Be My Valentine Card -->
                <Card class="group hover:scale-105 transition-all duration-500 bg-white/95 backdrop-blur-xl border-2 border-white/50 shadow-2xl hover:shadow-rose-500/20 overflow-hidden flex flex-col">
                    <CardHeader class="px-8 pt-10 pb-4 text-center relative flex-grow">
                        <!-- SVG Illustration -->
                        <div class="mb-6 flex justify-center">
                            <div class="relative animate-pulse">
                                <BeMine />
                            </div>
                        </div>

                        <CardTitle class="text-2xl font-bold text-gray-900">Be My Valentine</CardTitle>
                        <CardDescription class="text-base text-gray-600 mt-2 min-h-[3rem]">
                            Ask your crush to be your Valentine in the most romantic way possible.
                            <span class="hidden xl:inline">Make this day unforgettable!</span>
                        </CardDescription>
                    </CardHeader>

                    <CardContent class="px-8 pb-3">
                        <Button
                            class="w-full bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 text-white font-semibold py-6 text-lg shadow-lg hover:shadow-xl transition-all"
                            @click="() => navigateTo('/be-my-valentine')"
                        >
                            <Heart class="w-5 h-5 mr-2 fill-current" />
                            Ask Your Crush
                        </Button>
                    </CardContent>
                </Card>
            </div>

            <!-- Footer CTA -->
            <div class="text-center space-y-4">
                <p class="text-white/90 text-lg font-medium drop-shadow-md">
                    💕 Spread love, create memories, make magic happen 💕
                </p>
            </div>
        </div>

        <!-- Floating Hearts Animation -->
        <div class="fixed inset-0 pointer-events-none z-5 overflow-hidden">
            <div class="heart-float" style="left: 10%; animation-delay: 0s;">❤️</div>
            <div class="heart-float" style="left: 30%; animation-delay: 2s;">💕</div>
            <div class="heart-float" style="left: 50%; animation-delay: 4s;">💖</div>
            <div class="heart-float" style="left: 70%; animation-delay: 1s;">💝</div>
            <div class="heart-float" style="left: 90%; animation-delay: 3s;">💗</div>
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
    opacity: 0.6;
}

/* Ensure animations work */
@keyframes animate-in {
    from {
        opacity: 0;
        transform: translateY(1rem);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-in {
    animation: animate-in 0.6s ease-out;
}
</style>

