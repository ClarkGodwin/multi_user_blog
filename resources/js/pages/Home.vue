<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { ArticlesPaginated } from '@/types/article';
import CreateAnArticleLink from './components/CreateAnArticleLink.vue';

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

defineProps<{
    articles : ArticlesPaginated
}>()
</script>

<template>
    <Head title="Home" />

    <CreateAnArticleLink  v-if="articles.data.length == 0"/>

    <div v-else>

    </div>

</template>
