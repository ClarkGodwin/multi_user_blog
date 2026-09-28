<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { articleCreate } from '@/routes';

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

const formInputItems = [
    {
        label : 'Title',
        inputType : ''
    },
]

</script>

<template>
    <Head title="Create an article" />
</template>
