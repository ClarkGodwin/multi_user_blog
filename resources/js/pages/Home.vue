<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { dashboard } from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Home',
                href: dashboard(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Home" />

   
</template>
