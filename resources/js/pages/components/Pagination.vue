<script setup lang="ts">
import { computed } from 'vue';
import type { PaginationLinks } from '@/types/paginationLinks';
import { Link } from '@inertiajs/vue3';

type PageLink = PaginationLinks[number];

type CompactItem =
    | { type: 'link'; link: PageLink }
    | { type: 'ellipsis' };

const props = defineProps<{
    paginationLinks: PaginationLinks
}>();

function displayLabel(label: string): string {
    return label
        .replaceAll('&laquo;', '«')
        .replaceAll('&raquo;', '»');
}

const compactPagination = computed<CompactItem[]>(() => {
    const links = props.paginationLinks;
    const pageLinks = links
        .map((link) => ({ link, page: Number(link.label.trim()) }))
        .filter(({ link, page }) =>
            Number.isInteger(page) && page > 0 && link.label.trim() === String(page)
        );

    if (pageLinks.length === 0) {
        return [];
    }

    const currentPage = pageLinks.find(({ link }) => link.active)?.page ?? 1;
    const lastPage = Math.max(...pageLinks.map(({ page }) => page));

    const wantedPages = new Set([
        1, 2, 3,
        currentPage - 1, currentPage, currentPage + 1,
        lastPage,
    ]);

    const visiblePages = pageLinks.filter(({ page }) => wantedPages.has(page));
    const result: CompactItem[] = [];

    const previousLink = links[0];
    const nextLink = links[links.length - 1];

    if (previousLink) {
        result.push({ type: 'link', link: previousLink });
    }

    visiblePages.forEach((item, index) => {
        const previousPage = visiblePages[index - 1];

        if (previousPage && item.page - previousPage.page > 1) {
            result.push({ type: 'ellipsis' });
        }

        result.push({ type: 'link', link: item.link });
    });

    if (nextLink) {
        result.push({ type: 'link', link: nextLink });
    }

    return result;
});

</script>

<template>
    <!-- Pagination complète sur écran large -->
    <div v-if="paginationLinks.length > 3" class="hidden sm:flex flex-wrap items-center justify-end space-x-1 mt-4">
        <Link v-for="(link, index) in paginationLinks" :key="index" :href="link.url || '#'" :class="[
            link.active ? 'bg-dark-surface-200 dark:bg-surface-200 text-surface-200 dark:text-dark-surface-200' : 'bg-white text-gray-600',
            link.url ? '' : 'opacity-50 pointer-events-none',
        ]" class="px-3 text-[12px] rounded">
            {{ displayLabel(link.label) }}
        </Link>
    </div>

    <!-- Pagination réduite sur mobile -->
    <div v-if="paginationLinks.length > 3" class="flex sm:hidden flex-wrap items-center justify-end gap-1 mt-4">
        <template v-for="(item, index) in compactPagination" :key="index">
            <span v-if="item.type === 'ellipsis'" class="px-1">…</span>

            <Link v-else :href="item.link.url || '#'" :class="[
                item.link.active ? 'bg-dark-surface-200 dark:bg-surface-200 text-surface-200 dark:text-dark-surface-200' : 'bg-white text-gray-600',
                item.link.url ? '' : 'opacity-50 pointer-events-none',
            ]" class="px-2 py-1 text-[11px] rounded border">
                {{ displayLabel(item.link.label) }}
            </Link>
        </template>
    </div>
</template>
