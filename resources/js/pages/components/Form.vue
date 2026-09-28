<script setup lang="ts">
import { InputItem } from '@/types/inputItem';
import { Form } from '@inertiajs/vue3';
import Input from './Input.vue';

defineProps<{
    formTitle : string
    inputItems : InputItem[]
    submitText? : string
    action : string
    errors? : Record<string, string>
}>()
</script>

<template>
    <Form :action="action" method="post" #default="{ errors:formErrors }">
        <h2>
            {{ formTitle }}
        </h2>

        <div
        v-for="inputItem in inputItems"
        :key="inputItem.id"
        >
            <Input :input-item="inputItem" :error="formErrors[inputItem.name]"/>
        </div>
    </Form>
</template>
