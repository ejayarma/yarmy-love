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
                <div class="absolute inset-0 bg-card/95 backdrop-blur-xl border border-border"></div>

                <div class="relative p-8 text-center space-y-6">
                    <!-- PIN GATE -->
                    <template v-if="!revealed && requiresPincode">
                        <div class="flex justify-center">
                            <Lock class="w-12 h-12 text-primary" />
                        </div>
                        <h1 class="text-2xl font-bold">Enter PIN to View 💌</h1>

                        <Input v-model="pincode" maxlength="4" placeholder="4-digit PIN"
                            class="text-center tracking-widest" />

                        <p v-if="error" class="text-sm text-destructive">{{ error }}</p>

                        <!-- <Button @click="reveal" :disabled="loading" class="w-full">
                            <Spinner v-if="loading" class="mr-2" />
                            Unlock Message
                        </Button> -->
                        <Button @click="pinForm.post(BeMyValentineController.verify.url(token))"
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
                            {{ data.crushName }}, will you be my Valentine? 💕
                        </h1>

                        <p class="text-lg text-muted-foreground">
                            From <strong>{{ data.authorName }}</strong>
                        </p>

                        <div class="bg-secondary rounded-xl p-6 text-left">
                            <p class="whitespace-pre-line">{{ data.message }}</p>
                        </div>

                        <div class="flex justify-center gap-4 pt-4 relative">
                            <!-- <Button class="px-10" @click="respond('yes')">
                                Yes 💖
                            </Button>

                            <Button variant="outline" class="px-10 transition-transform" @click="respond('no')"
                                @mouseenter="data.forceYes ? dodgeNo : null">
                                No 💔
                            </Button> -->
                            <Button
                                @click="responseForm.post(BeMyValentineController.respond.url(token))">
                                Yes 💖
                            </Button>

                            <Button variant="outline" @mouseenter="forceYes ? dodgeNo : null"
                                @click="responseForm.post(BeMyValentineController.respond.url(token))">
                                No 💔
                            </Button>

                        </div>
                    </template>

                    <!-- NO PIN REQUIRED -->
                    <!-- <template v-else>
                        <Button @click="reveal" class="w-full">
                            View Valentine 💘
                        </Button>
                    </template> -->
                </div>
            </div>
        </div>
    </AppLayout>
</template>
