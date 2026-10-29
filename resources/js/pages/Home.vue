<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { ArticlesPaginated } from '@/types/article';
import CreateAnArticleLink from './components/CreateAnArticleLink.vue';
import Pagination from './components/Pagination.vue';
import ArticleView from './components/ArticleView.vue';
import { details } from '@/routes/article/index.js';
import { User } from '@/types/auth.js';
import Search from './components/Search.vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Home',
                href: dashboard(),
            },
        ],
        showSearch: true,
    },
});

defineProps<{
    auth: {
        user: User
    }
    articles: ArticlesPaginated
}>()
</script>

<template>

    <Head title="Home" />

    <CreateAnArticleLink v-if="articles.data.length == 0" />

    <div v-else>
        <Pagination class="mb-10" :paginationLinks="articles.links" />

        <Link v-for="article in articles.data" :key="article.id" :href="details(article)">
            <ArticleView :article="article" :user="article.user!" />
        </Link>

    </div>

</template>
