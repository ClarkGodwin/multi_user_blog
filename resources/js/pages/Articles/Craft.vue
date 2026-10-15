<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { toAuthor } from '@/routes';
import { User, UserRoleEnum } from '@/types';
import Button from '../components/Button.vue';
import type { ArticlesPaginated } from '@/types/article.js';
import { craft, create, details } from '@/routes/article/index.js';
import ArticleView from '../components/ArticleView.vue';
import Pagination from '../components/Pagination.vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Craft',
                href: craft(),
            },
        ],
    },
});

const props = defineProps<{
    auth: {
        user: User
    }
    articles: ArticlesPaginated
}>()
console.log(props.articles)
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
            <Pagination class="mb-10" :paginationLinks="articles.links" />

            <Link v-for="article in articles.data" :key="article.id" :href="details(article.id)">
                <ArticleView :article="article" :user="auth.user" />
            </Link>

        </div>
    </div>
</template>
