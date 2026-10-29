<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

const { breadcrumbs = [] } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
    showSearch?: boolean;
}>();
</script>

<template>
    <AppLayout :show-search="showSearch" :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>
