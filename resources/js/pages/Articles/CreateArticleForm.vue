<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { articles } from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Articles',
                href: articles(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Articles" />
</template>
