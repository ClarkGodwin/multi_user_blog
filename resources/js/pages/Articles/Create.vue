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
        type : {
            kind : 'InputType',
            type : 'text'
        },
        name : 'title',
        required : true,
    },
    {
        id : 2,
        label : 'Content',
        type : {
            kind : 'TextareaType',
            type : 'textarea'
        },
        name : 'content',
        required : true,
    },
    {
        id : 3,
        label : 'Save to Published or Craft',
        type : {
            kind: 'SelectType',
            type: 'select',
            options: ['Published', 'Craft']
        },
        name : 'status',
        required : true,
    },
]

</script>

<template>
    <Head title="Create an article" />
    <Form formTitle="Create an article" :action="store()" :input-items="inputItems"/>
</template>
