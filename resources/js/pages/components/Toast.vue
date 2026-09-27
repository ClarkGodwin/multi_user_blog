<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { CircleCheck, CircleX, InfoIcon } from '@lucide/vue';
import {ref, watch } from 'vue';

const page = usePage()

const toastVisible = ref(false)

const background = ref('') //the background of the toast
const toastText = ref('') //the text displayed on the toast
const successVisible = ref(false) //to show the circlecheck icon if we have a success flash message
const errorVisible = ref(false) //to show the circlex icon if we have a error flash message
const statusVisible = ref(false) //to show the info icon if we have a status flash message

watch( //to watch if any flash message was sent to one of the components with the toast component mounted in one of their layout and displays it for two seconds
    () => page.flash,
    (flash) => {
        if (!flash?.success && !flash?.error && !flash?.status) return
        if (flash.success) {
            background.value = 'bg-frosted'
            toastText.value = flash.success
            successVisible.value = true
        }
        else if (flash.error) {
            background.value = 'bg-red-500'
            toastText.value = flash.error
            errorVisible.value = true
        }
        else if (flash.status) {
            background.value = 'bg-yellow-500'
            toastText.value = flash.status
            statusVisible.value = true
        }
        toastVisible.value = true
        setTimeout(() => {
            toastVisible.value = false
        }, 3000);
    },
    { deep: true }
)

</script>

<template>
    <div v-if="toastVisible" class="relative left-toast-left-position ">
        <div class="font-bold text-white text-toast h-fit flex items-center justify-center gap-toast-gap  p-toast-p rounded-xl fixed top-toast-top-position"
            :class="background">
            <CircleCheck v-if="successVisible" class="size-(--text-toast)" />
            <CircleX v-if="errorVisible" class="size-(--text-toast)" />
            <InfoIcon v-if="statusVisible" class="size-(--text-toast)" />
            <span>{{ toastText }}</span>
        </div>
    </div>
</template>
