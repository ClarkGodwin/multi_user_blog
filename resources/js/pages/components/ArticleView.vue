<script setup lang="ts">
import { User } from '@/types';
import { Article } from '@/types/article';
import { usePage } from '@inertiajs/vue3';

defineProps<{
    user : User
    article : Article
}>()

const authenticatedUser = usePage().props.auth.user

</script>

<template>
    <section class=" w-article-view mx-auto py-4 flex flex-col gap-3 border-b">
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
    </section>
</template>
