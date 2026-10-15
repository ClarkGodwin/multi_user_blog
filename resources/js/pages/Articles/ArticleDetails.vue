<script setup lang="ts">
import { Head, } from '@inertiajs/vue3';
import { User } from '@/types/auth.js';
import { Article } from '@/types/article.js';

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
}>()
</script>

<template>
    <Head title="Details of an Article" />
    {{ userAuthor.name }}
    {{ article.content }}
</template>
