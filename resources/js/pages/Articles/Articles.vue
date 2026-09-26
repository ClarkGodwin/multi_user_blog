<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { articles } from '@/routes';
import { User, UserRoleEnum } from '@/types';
import Button from '../components/Button.vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Articles',
                href: articles(),
            },
        ],
    },
});

defineProps<{
    auth: {
        user: User
    }
}>()
</script>

<template>

    <Head title="Articles" />

    <!-- for the part where the user has not yet an author role -->
    <div
    v-if="auth.user.role != UserRoleEnum.Author"
    class="w-fit mx-auto flex flex-col items-center gap-articles-notAnAuthor-gap mt-articles-notAnAuthor-mt text-articles-notAnAuthor"
    >
        <div>It seems like you're trying to create articles without being registered as an author</div>
        <Button class="w-fit">Become an author</Button>
    </div>

    <div v-else>
        you're an author now
    </div>
</template>
