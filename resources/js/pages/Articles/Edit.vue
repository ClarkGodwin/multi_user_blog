<script setup lang="ts">
import { Head, } from '@inertiajs/vue3';
import Form from '../components/Form.vue';
import { edit, update } from '@/routes/article/index.js';
import { Article } from '@/types/article.js';
import { getArticleFormInputItem } from '@/composables/useArticleFormInputItems.js';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Edit the article',
                href: edit,
            },
        ],
    },
});

defineProps<{
    article : Article
}>()

</script>

<template>
    <Head title="Edit the article" />
    <Form formTitle="Edit the article" :action="update(article)" :input-items="getArticleFormInputItem(true, article)"/>
</template>
