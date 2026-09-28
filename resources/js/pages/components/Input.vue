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
import { computed } from 'vue';

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
            <InputGroupInput v-if="inputItem.type.kind == 'InputType'" :placeholder='inputItem.label' :required="inputItem.required"/>
            <InputGroupTextarea v-if="inputItem.type.kind == 'TextareaType'" :placeholder="inputItem.label" :required="inputItem.required"></InputGroupTextarea>
            <InputGroupAddon>
                <component v-if="inputItem.icon" :is="inputItem.icon" />
            </InputGroupAddon>
        </InputGroup>
    </div>
</template>
