<script setup lang="ts">
import { Head, usePage, } from '@inertiajs/vue3';
import { User } from '@/types/auth.js';
import { Article } from '@/types/article.js';
import ArticleView from '../components/ArticleView.vue';
import CommentView from '../components/CommentView.vue';
import { Comment } from '@/types/comment.js';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Details of an article',
            },
        ],
    },
});

defineProps<{
    auth: {
        user: User
    }
    userAuthor :  User
    article: Article
    comments?: Comment[]
}>()

const selectedCommentToShowItsDetails = ref<Comment | null>(null)

const savedComment = localStorage.getItem('selectedComment');

selectedCommentToShowItsDetails.value = savedComment
    ? JSON.parse(savedComment) as Comment
    : null;

function clickToSelectACommentToShowItsDetails(comment?: Comment){
    if(comment){
        selectedCommentToShowItsDetails.value = comment
        localStorage.setItem('selectedComment', JSON.stringify(comment))
    }
    else{
        selectedCommentToShowItsDetails.value = null
    }
}

</script>

<template>
    <Head title="Details of an Article" />

    <ArticleView :article="article" :user="userAuthor" isDetails/>

    <CommentView v-if="selectedCommentToShowItsDetails" :comment="selectedCommentToShowItsDetails" isDetails/>

    <div v-else
    v-for="comment in comments" :key="comment.id"
    @click="clickToSelectACommentToShowItsDetails(comment)"
    class="hover:cursor-pointer"
    >
        <CommentView :comment="comment" />
    </div>

</template>
