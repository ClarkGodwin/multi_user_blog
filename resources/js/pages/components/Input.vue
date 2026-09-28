<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { InputItem } from '@/types/input';

defineProps<{
    inputItem : InputItem
    error? : string
}>()
</script>

<template>
    <div>
        <label v-if="inputItem.label" :for="inputItem.label">{{ inputItem.label }}</label>
        <Input/>
    </div>
</template>
