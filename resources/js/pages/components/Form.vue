<script setup lang="ts">
import { InputItem } from '@/types/inputItem';

defineProps<{
    formTitle : string
    inputItems : InputItem[]
    submitText? : string
    action : string
    errors? : Record<string, string>
}>()
</script>

<template></template>
