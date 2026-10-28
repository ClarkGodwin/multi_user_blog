<script setup lang="ts">
import { Head, } from '@inertiajs/vue3';
import { InputItem } from '@/types/input.js';
import Form from '../components/Form.vue';
import { create, store } from '@/routes/article/index.js';
import { getArticleFormInputItem } from '@/composables/useArticleFormInputItems.js';
import { User, UserRoleEnum } from '@/types/auth.js';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Create an article',
                href: create,
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
    <Head title="Create an article" />
    <BecomeAnAuthor v-if="auth.user.role != UserRoleEnum.Author"/>
    <Form v-else formTitle="Create an article" :action="store()" :input-items="getArticleFormInputItem()"/>
</template>
