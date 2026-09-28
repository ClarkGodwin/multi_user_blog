<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { InputItem } from '@/types/inputItem';

defineProps<{
    inputItem : InputItem
    error? : string
}>()
</script>

<template>
    <Input/>
</template>
