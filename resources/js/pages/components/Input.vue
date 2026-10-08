<script setup lang="ts">
import { Input } from '@/components/ui/input';
import Label from '@/components/ui/label/Label.vue';

import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'
import { Textarea } from '@/components/ui/textarea';

import { InputItem } from '@/types/input';

defineProps<{
    inputItem: InputItem
    error?: string
}>()

</script>

<template>
    <Label v-if="inputItem.type.type != 'hidden'" :for="inputItem.name" class="mb-1">{{ inputItem.label }}</Label>
    <Input v-if="inputItem.type.kind == 'InputType'" :type="inputItem.type.type" :name="inputItem.name" :required="inputItem.required"/>

    <Select v-else-if="inputItem.type.kind == 'SelectType'" :name="inputItem.name"  :required="inputItem.required" class="w-full h-full absolute inset-0">
        <SelectTrigger class="w-full">
            <SelectValue />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem v-for="option in inputItem.type.options" :value="option.toLowerCase()">
                    {{ option }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>

    <Textarea v-else :name="inputItem.name" :required="inputItem.required"/>

    <div v-if="error" class="text-red-500">{{ error }}</div>

</template>
