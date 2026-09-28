<script setup lang="ts">
import { Head, } from '@inertiajs/vue3';
import { InputItem } from '@/types/input.js';
import Form from '../components/Form.vue';
import { create, store } from '@/routes/article/index.js';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Create an article',
                href: create,
            },
        ],
    },
});

const inputItems : InputItem[] = [
    {
        id : 1,
        label : 'Title',
        type : 'text',
        name : 'title',
        required : true
    },
    {
        id : 2,
        label : 'Content',
        type : 'text',
        name : 'content',
        required : true
    },
    {
        id : 3,
        label : 'Save to Published or Craft',
        type : 'text',
        name : 'status',
        required : true
    },
]

</script>

<template>
    <Head title="Create an article" />
    <Form formTitle="Create an article" :action="store()" :input-items="inputItems"/>
</template>
