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
    <Input v-if="inputItem.type.kind == 'InputType'" :placeholder="inputItem.label" />

    <Select v-else-if="inputItem.type.kind == 'SelectType'">
        <SelectTrigger class="w-full">
            <SelectValue :placeholder="inputItem.label" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem v-for="option in inputItem.type.options" :value="option">
                    {{ option }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>

    <Textarea v-else :placeholder="inputItem.label"/>
</template>
