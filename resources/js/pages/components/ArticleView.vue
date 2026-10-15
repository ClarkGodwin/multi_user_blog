<script setup lang="ts">
import { details } from '@/routes/article';
import { Article } from '@/types/article';
import { Link } from '@inertiajs/vue3';

defineProps<{
    userName : string
    userAvatar? : string
    article : Article
}>()

</script>

<template>
    <Link
    :href="details(article.id)"
    class=" w-article-view mx-auto py-4 flex flex-col gap-3 border-b"
    >
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                <img
                :src="userAvatar ? userAvatar : '/storage/images/avatars/default.jpg'"
                alt=""
                class="rounded-sm sm:rounded-lg w-article-view-avatar"
                >

                <span class="text-article-view-name">{{ userName }}</span>
            </div>
        </div>

        <div class="text-center text-article-view-title">{{ article.title }}</div>

        <div class="text-article-view-content">
            {{ article.content }}
        </div>
    </Link>
</template>
