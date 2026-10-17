<script setup lang="ts">
import { updateForm } from '@/routes/article';
import { User } from '@/types';
import { Article } from '@/types/article';
import { Link } from '@inertiajs/vue3';
import { PencilLine, Trash2 } from '@lucide/vue';

defineProps<{
    user : User
    article : Article
    isDetails? : boolean
}>()

</script>

<template>
    <section
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

            <div v-if="isDetails" class="flex gap-2.5 **:size-(--text-article-view-content)">
                <Link :href="updateForm(article.id)">
                    <PencilLine class="text-green-500"/>
                </Link>

                <Link>
                    <Trash2 class="text-red-500"/>
                </Link>
            </div>
        </div>

        <div class="text-center text-article-view-title font-bold">{{ article.title }}</div>

        <div class="text-article-view-content">
            {{ isDetails ? article.content : article.content.substring(0, 500) + '...' }}
        </div>
    </section>
</template>
