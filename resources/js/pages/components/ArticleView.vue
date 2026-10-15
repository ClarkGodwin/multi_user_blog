<script setup lang="ts">
import { details } from '@/routes/article';
import { User } from '@/types';
import { Article } from '@/types/article';
import { Link } from '@inertiajs/vue3';

defineProps<{
    user : User
    article : Article
}>()

</script>

<template>
    <Link
    :href="details()"
    class=" w-article-view mx-auto py-4 flex flex-col gap-3 border-b"
    >
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                <img
                :src="user.avatar ? user.avatar : '/storage/images/avatars/default.jpg'"
                alt=""
                class="rounded-sm sm:rounded-lg w-article-view-avatar"
                >

                <span class="text-article-view-name">{{ user.name }}</span>
            </div>
        </div>

        <div class="text-center text-article-view-title">{{ article.title }}</div>

        <div class="text-article-view-content">
            {{ article.content }}
        </div>
    </Link>
</template>
