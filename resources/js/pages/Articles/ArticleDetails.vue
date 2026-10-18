<script setup lang="ts">
import { Head, } from '@inertiajs/vue3';
import { User } from '@/types/auth.js';
import { Article } from '@/types/article.js';
import ArticleView from '../components/ArticleView.vue';
import { Comment } from '@/types/comment.js';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Details of an article',
            },
        ],
    },
});

defineProps<{
    auth: {
        user: User
    }
    userAuthor :  User
    article: Article
    comments: Comment[]
}>()
</script>

<template>
    <Head title="Details of an Article" />
    <ArticleView :article="article" :user="userAuthor" :comments="comments" isDetails/>
</template>
