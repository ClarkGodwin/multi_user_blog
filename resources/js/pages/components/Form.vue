<script setup lang="ts">
import { InputItem } from '@/types/input';
import { Form } from '@inertiajs/vue3';
import Input from './Input.vue';
import { RouteDefinition } from '@/wayfinder/index.js';
import Button from './Button.vue';

defineProps<{
    formTitle: string
    inputItems: InputItem[]
    submitText?: string
    action: RouteDefinition<'post'> | RouteDefinition<'put'>
    errors?: Record<string, string>
}>()
</script>

<template>
    <Form
    :action="action"
    method="post"
    #default="{ errors: formErrors }"
    class="flex flex-col justify-center gap-[10px] min-h-[70%] w-form mx-auto">
        <h2 class="text-center font-bold text-[25px] text-dark-surface-300">
            {{ formTitle }}
        </h2>

        <Input v-for="inputItem in inputItems" :key="inputItem.id" :inputItem="inputItem"
            :error="formErrors[inputItem.name]" />

        <Button type="submit">{{ submitText ? submitText : 'Submit' }}</Button>
    </Form>
</template>
