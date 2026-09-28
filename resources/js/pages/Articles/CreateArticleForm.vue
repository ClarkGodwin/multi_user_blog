<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { articleCreate } from '@/routes';
import { InputItem } from '@/types/inputItem';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Create an article',
                href: articleCreate(),
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
</template>
