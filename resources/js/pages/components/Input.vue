<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { InputItem } from '@/types/input';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupButton,
    InputGroupInput,
    InputGroupText,
    InputGroupTextarea,
} from '@/components/ui/input-group'
import { SearchIcon } from '@lucide/vue';

defineProps<{
    inputItem: InputItem
    error?: string
}>()
</script>

<template>
    <div>
        <!-- <label v-if="inputItem.label" :for="inputItem.label">{{ inputItem.label }}</label> -->
        <!-- <Input/> -->
        <InputGroup>
            <InputGroupInput v-if="inputItem.label" :placeholder='inputItem.label' :required="inputItem.required"/>
            <InputGroupAddon>
                <component v-if="inputItem.icon" :is="inputItem.icon" />
            </InputGroupAddon>
        </InputGroup>
    </div>
</template>
