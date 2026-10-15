<script setup lang="ts">
import { PaginationLinks } from '@/types/paginationLinks';
import { Link } from '@inertiajs/vue3';


defineProps<{
    paginationLinks: PaginationLinks
}>()

</script>

<template>
    <div v-if="paginationLinks.length > 3" class="flex flex-wrap items-center justify-end space-x-1 mt-4">
        <Link v-for="(link, index) in paginationLinks" :key="index" :href="link.url || '#'" :class="[
            link.active ? 'bg-blue-600 text-white' : 'bg-white  text-gray-600',
            link.url ? 'hover:bg-gray-100' : 'opacity-50 pointer-events-none',
        ]"
        class="px-3 text-sm rounded border"
        v-html="link.label"
        >
        </Link>
    </div>
</template>
