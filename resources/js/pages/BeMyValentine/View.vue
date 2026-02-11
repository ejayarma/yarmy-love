<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { Heart, Lock } from 'lucide-vue-next'
import { ref } from 'vue'
import BeMyValentineController from '@/actions/App/Http/Controllers/BeMyValentineController'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
// import { Spinner } from '@/components/ui/spinner'
import AppLayout from '@/layouts/AppLayout.vue'


const pinForm = useForm({
    pincode: '',
})

const responseForm = useForm({
    response: 'yes' as 'yes' | 'no',
})

const props = defineProps<{
    token: string
    requiresPincode: boolean
    forceYes: boolean
    crushName: string
    authorName: string
    message: string
}>()

const loading = ref(false)
const error = ref<string | null>(null)
const revealed = ref(false)

const pincode = ref('')
const data = ref<{
    authorName: string
    crushName: string
    message: string
    forceYes: boolean
} | null>(null)

const verifyPin = () => {
    pinForm.post(BeMyValentineController.verify.url(props.token), {
        onSuccess: () => {
            revealed.value = true
        },
    })
}

const reveal = async () => {
    loading.value = true
    error.value = null

    try {
        const res = await fetch(`/valentine/${props.token}/verify`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ pincode: pincode.value }),
        })

        const json = await res.json()

        if (!json.success) {
            error.value = json.error
        } else {
            data.value = json
            revealed.value = true
        }
    } catch {
        error.value = 'Something went wrong. Please try again.'
    } finally {
        loading.value = false
    }
}

const respond = async (response: 'yes' | 'no') => {
    await fetch(`/valentine/${props.token}/respond`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ response }),
    })
}

const dodgeNo = (e: MouseEvent) => {
    const btn = e.target as HTMLElement
    btn.style.transform = `translate(${Math.random() * 120 - 60}px, ${Math.random() * 80 - 40}px)`
}
</script>

<template>
    <AppLayout>

        <Head title="A Valentine Question 💖" />

        <div class="min-h-screen flex items-center justify-center px-4">
            <div class="w-full max-w-xl relative rounded-2xl overflow-hidden shadow-2xl">

                <div class="fixed inset-0 z-0"
                    style="background-image: url('/rose-petals.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-900/40 via-rose-800/30 to-pink-900/40">
                    </div>
                </div>

                <div class="relative z-10 p-8 text-center space-y-6">
                    <div class="absolute inset-0 h-full bg-white/95 backdrop-blur-xl border-2 border-white/50"></div>

                    <!-- PIN GATE -->
                    <div class="relative z-20 space-y-5">
                        <template v-if="!revealed && requiresPincode">
                            <div class="flex justify-center">
                                <Lock class="w-12 h-12 text-primary" />
                            </div>
                            <h1 class="text-2xl font-bold">Enter PIN to View 💌</h1>

                            <Input autofocus v-model="pincode" maxlength="4" placeholder="4-digit PIN"
                                class="text-center tracking-widest" />

                            <p v-if="error" class="text-sm text-destructive">{{ error }}</p>

                            <Button
                                class="flex-1 bg-linear-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700"
                                @click="verifyPin"
                                :disabled="pinForm.processing">
                                Unlock Message
                            </Button>

                            <p v-if="pinForm.errors.pincode" class="text-destructive">
                                {{ pinForm.errors.pincode }}
                            </p>

                        </template>

                        <!-- MESSAGE VIEW -->
                        <template v-else-if="revealed && data">
                            <div class="flex justify-center">
                                <Heart class="w-14 h-14 text-primary fill-current animate-pulse" />
                            </div>

                            <h1 class="text-3xl font-bold">
                                {{ props?.crushName }}, will you be my Valentine? 💕
                            </h1>

                            <p class="text-lg text-muted-foreground">
                                From <strong>{{ props?.authorName }}</strong>
                            </p>

                            <div class="bg-secondary rounded-xl p-6 text-left">
                                <p class="whitespace-pre-line">{{ props?.message }}</p>
                            </div>

                            <!-- <div class="flex justify-center gap-4 pt-4 relative">
                                <Button
                                    class="flex-1 bg-linear-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700"
                                    @click="responseForm.post(BeMyValentineController.respond.url(token))">
                                    Yes 💖
                                </Button>

                                <Button
                                    class="flex-1 bg-linear-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700"
                                    variant="outline" @mouseenter="props.forceYes ? dodgeNo : null"
                                    @click="responseForm.post(BeMyValentineController.respond.url(token))">
                                    No 💔
                                </Button>
                            </div> -->

                            <!-- Yes button -->
                            <Button
                                class="flex-1 bg-linear-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700"
                                @click="responseForm.post(BeMyValentineController.respond.url(token))">
                                Yes 💖
                            </Button>

                            <!-- "No" button – but not really -->
                            <Button
                                class="flex-1 bg-linear-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700"
                                variant="outline" @mouseenter="props.forceYes ? dodgeNo : null"
                                @click="responseForm.post(BeMyValentineController.respond.url(token))">
                                No 💔
                            </Button>

                        </template>
                        <!-- NO PIN REQUIRED -->
                        <div>
                            <Button
                                class="flex-1 w-full bg-linear-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700"
                                @click="reveal">
                                View Valentine
                                <Heart class="w-5 h-5 ml-2 fill-current" />
                            </Button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
