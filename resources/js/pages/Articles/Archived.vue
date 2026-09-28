<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { toAuthor } from '@/routes';
import { User, UserRoleEnum } from '@/types';
import Button from '../components/Button.vue';
import type { ArticlesPaginated } from '@/types/article.js';
import { archived, create } from '@/routes/article/index.js';

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
    <div v-if="auth.user.role != UserRoleEnum.Author"
        class="w-fit mx-auto flex flex-col justify-center items-center gap-articles-notAnAuthor-gap min-h-[90%] text-articles-notAnAuthor">
        <div>It seems like you're trying to create articles without being registered as an author</div>
        <Link :href="toAuthor()" method="post">
            <Button class="w-fit hover:cursor-pointer">
                Become an author
            </Button>
        </Link>
    </div>

    <!-- for the part where the user has an author role -->
    <div v-else class="min-h-[90%]">
        <div v-if="articles.data.length == 0"
            class="w-fit mx-auto flex flex-col justify-center items-center gap-articles-notAnAuthor-gap min-h-[90%] text-articles-notAnAuthor">
            <div>
                It seems like you've never created an article
            </div>
            <Link :href="create()">
                <Button class="w-fit hover:cursor-pointer">
                    Create an article
                </Button>
            </Link>
        </div>

        <div v-else>
            <div v-for="article in articles.data" :key="article.id">
                {{ article.title }}
            </div>
        </div>
    </div>
</template>
