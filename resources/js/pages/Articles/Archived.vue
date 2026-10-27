<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { toAuthor } from '@/routes';
import { User, UserRoleEnum } from '@/types';
import Button from '../components/Button.vue';
import type { ArticlesPaginated } from '@/types/article.js';
import { archived, create } from '@/routes/article/index.js';
import BecomeAnAuthor from '../components/BecomeAnAuthor.vue';
import CreateAnArticleLink from '../components/CreateAnArticleLink.vue';

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
            <div v-for="article in articles.data" :key="article.id">
                {{ article.title }}
            </div>
        </div>
    </div>
</template>
