<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { article} from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Article',
                href: article(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Article" />
</template>
