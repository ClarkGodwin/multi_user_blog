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
    <section
    class=" w-article-details mx-auto py-4 flex flex-col gap-3 border-b"
    >
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                <img
                :src="userAuthor.avatar ? userAuthor.avatar : '/storage/images/avatars/default.jpg'"
                alt=""
                class="rounded-sm sm:rounded-lg w-article-details-avatar"
                >

                <span class="text-article-details-name">{{ userAuthor.name }}</span>
            </div>
        </div>

        <div class="text-center text-article-details-title">{{ article.title }}</div>

        <div class="text-article-details-content">
            {{ article.content }}
        </div>
    </section>
</template>
