<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Heart, CheckCircle } from 'lucide-vue-next';

type Props = {
    valentine: {
        crush_name: string;
        response: 'yes' | 'no' | 'maybe';
        responded_at: string;
    };
};

const props = defineProps<Props>();

</script>

<template>

    <Head title="Already Responded" />

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="fixed inset-0 z-0"
            style="background-image: url('/rose-petals.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <div class="absolute inset-0 bg-linear-to-br from-red-900/40 via-rose-800/30 to-pink-900/40"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-lg">
                <!-- Main Card -->
                <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                    <div class="absolute inset-0 bg-white/95 backdrop-blur-xl border-2 border-white/50"></div>
                    <div class="relative p-12">
                        <div class="text-center space-y-6">
                            <!-- Icon -->
                            <div class="flex justify-center">
                                <div class="p-6 rounded-full bg-primary/10">
                                    <CheckCircle class="w-16 h-16 text-primary" />
                                </div>
                            </div>

                            <!-- Title -->
                            <h1 class="text-3xl font-bold text-gray-900">
                                Already Responded
                            </h1>

                            <!-- Message -->
                            <div class="space-y-4">
                                <p class="text-lg text-gray-600">
                                    Hey {{ valentine.crush_name }}, you've already responded to this Valentine request.
                                </p>

                                <div class="bg-primary/5 border-2 border-primary/20 rounded-xl p-6">
                                    <p class="text-sm text-slate-800">
                                        <strong>Your response: </strong>
                                        <span v-if="valentine.response === 'yes'" class="rounded px-2 py-1 bg-green-600  text-white uppercase">Yes! 💖</span>
                                        <span v-else-if="valentine.response === 'maybe'" class="rounded px-2 py-1 bg-yellow6700 text-white uppercase">Maybe💭</span>
                                        <span v-else class="rounded px-2 py-1 bg-gray-600  text-white uppercase">No 💔</span>
                                    </p>
                                    <p class="text-xs text-slate-600 mt-2">
                                        Responded on {{ new Date(valentine.responded_at).toLocaleDateString() }}
                                    </p>
                                </div>

                                <p class="text-sm text-gray-500">
                                    Once you respond to a Valentine request, it cannot be changed. Thank you for being
                                    honest! 💕
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
