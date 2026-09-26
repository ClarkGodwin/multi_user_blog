<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { articles } from '@/routes';
import { User, UserRole } from '@/types';
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
    auth : {
        user : User
    }
}>()
</script>

<template>
    <Head title="Articles" />
    <div v-if="auth.user.role != UserRole.Author">
        <Button>Become an author</Button>
    </div>
</template>
