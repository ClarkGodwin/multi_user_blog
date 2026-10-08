<script setup lang="ts">
import { Input } from '@/components/ui/input';

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
    <Input v-if="inputItem.type.kind == 'InputType'" :type="inputItem.type.type" :name="inputItem.name" :placeholder="inputItem.label" :required="inputItem.required"/>

    <Select v-else-if="inputItem.type.kind == 'SelectType'" :name="inputItem.name"  :required="inputItem.required" class="w-full h-full absolute inset-0">
        <SelectTrigger class="w-full">
            <SelectValue :placeholder="inputItem.label" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem v-for="option in inputItem.type.options" :value="option.toLowerCase()">
                    {{ option }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>

    <Textarea v-else :placeholder="inputItem.label" :name="inputItem.name" :required="inputItem.required"/>

    <div v-if="error" class="text-red-500">{{ error }}</div>

</template>
