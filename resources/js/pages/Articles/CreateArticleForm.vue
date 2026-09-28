<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { articleCreate, articleCreateForm } from '@/routes';
import { InputItem } from '@/types/inputItem';
import Form from '../components/Form.vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Create an article',
                href: articleCreateForm(),
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
        type : 'textarea',
        name : 'content',
        required : true
    },
    {
        id : 3,
        label : 'Save to Published or Craft',
        type : 'select',
        name : 'status',
        required : true
    },
]

</script>

<template>
    <Head title="Create an article" />
    <Form formTitle="Create an article" :action="createArticle()" :input-items="inputItems"/>
</template>
