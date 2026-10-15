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
    <section class=" w-[900px] mx-auto py-4 flex flex-col gap-3 border-b">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                <img
                :src="user.avatar ? user.avatar : '/storage/images/avatars/default.jpg'"
                alt=""
                class="rounded-full w-[30px]"
                >

                <span class="text-[14px]">{{ user.name }}</span>
            </div>
        </div>

        <div class="text-center text-[30px]">{{ article.title }}</div>

        <div>
            {{ article.content }}
        </div>
    </section>
</template>
