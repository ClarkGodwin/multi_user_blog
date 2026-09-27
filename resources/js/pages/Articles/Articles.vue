<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { articles, toAuthor, toReader } from '@/routes';
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
    <div v-else>
        you're an author now
        <Link :href="toReader()" method="post" class="">
            <Button class="w-fit hover:cursor-pointer">
                Become a reader
            </Button>
        </Link>
    </div>
</template>
