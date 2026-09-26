<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { articles } from '@/routes';
import { User, UserRoleEnum } from '@/types';
import { Button } from '@/components/ui/button';

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
    class="w-fit mx-auto flex flex-col mt-[100px]"
    >
        <div>It seems like you're trying to create articles without being registered as an author</div>
        <Button class="w-fit">Become an author</Button>
    </div>

    <div v-else>
        you're an author now
    </div>
</template>
