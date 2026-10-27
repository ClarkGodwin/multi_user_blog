<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { toAuthor } from '@/routes';
import { User, UserRoleEnum } from '@/types';
import Button from '../components/Button.vue';
import type { ArticlesPaginated } from '@/types/article.js';
import { archived, create, details } from '@/routes/article/index.js';
import BecomeAnAuthor from '../components/BecomeAnAuthor.vue';
import CreateAnArticleLink from '../components/CreateAnArticleLink.vue';
import ArticleView from '../components/ArticleView.vue';
import Pagination from '../components/Pagination.vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Archived',
                href: archived(),
            },
        ],
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

    <Head title="Articles" />

    <!-- for the part where the user has not yet an author role -->
    <BecomeAnAuthor v-if="auth.user.role != UserRoleEnum.Author"/>

    <!-- for the part where the user has an author role -->
    <div v-else class="min-h-[90%]">
        <CreateAnArticleLink  v-if="articles.data.length == 0"/>

        <div v-else>
            <Pagination class="mb-10" :paginationLinks="articles.links" />

            <Link v-for="article in articles.data" :key="article.id" :href="details(article)">
                <ArticleView :article="article" :user="auth.user" />
            </Link>
        </div>
    </div>
</template>
