<script setup lang="ts">
import { Head, } from '@inertiajs/vue3';
import { InputItem } from '@/types/input.js';
import Form from '../components/Form.vue';
import { create, store } from '@/routes/article/index.js';
import { getArticleFormInputItem } from '@/composables/useArticleFormInputItems.js';

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

</script>

<template>
    <Head title="Create an article" />
    <Form formTitle="Create an article" :action="store()" :input-items="getArticleFormInputItem()"/>
</template>
