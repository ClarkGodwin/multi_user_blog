<script setup lang="ts">
import { Head, } from '@inertiajs/vue3';
import { InputItem } from '@/types/input.js';
import Form from '../components/Form.vue';
import { create, store } from '@/routes/article/index.js';
import { FileTypeCorner, SquareText, Tag, TypeOutline } from '@lucide/vue';

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
        icon : FileTypeCorner
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
        icon : SquareText
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
        icon: Tag
    },
]

</script>

<template>
    <Head title="Create an article" />
    <Form formTitle="Create an article" :action="store()" :input-items="inputItems"/>
</template>
