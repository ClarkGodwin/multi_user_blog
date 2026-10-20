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
    userAuthor: User
    article: Article
    comments?: Comment[]
}>()

/**
 * Instead of a link on each comment which will imply to pass by the server to display the details, i decided to handle that locally, when this variable is null, the overview will be displayed, if it contains a comment, its details will  be displayed
 */
const selectedCommentToShowItsDetails = ref<Comment | null>(null)

/**
 * the localstorage is there so that the selectedCommentToShowItsDetails doesn't return to null if  the user refresh the page
 */
const savedComment = localStorage.getItem('selectedComment');

selectedCommentToShowItsDetails.value = savedComment
    ? JSON.parse(savedComment) as Comment
    : null;

function clickToSelectACommentToShowItsDetails(comment?: Comment) {
    if (comment) {
        selectedCommentToShowItsDetails.value = comment
        localStorage.setItem('selectedComment', JSON.stringify(comment))

        window.history.pushState(
            { ...window.history.state },
            '',
            window.location.href,
        )
    }
    else {
        selectedCommentToShowItsDetails.value = null
    }
}

/**
 * this function is there to give to the user an illusion of browser navigation when he's navigating back and forth between the overview of the comments of an article and the details of that comment since the display of the comment is handled locally.
 */
function handleNavigationBrowserInThisComponent() {
    if (selectedCommentToShowItsDetails.value !== null) {
        clickToSelectACommentToShowItsDetails();
    }
}

</script>

<template>

    <Head title="Details of an Article" />

    <ArticleView :article="article" :user="userAuthor" isDetails />

    <CommentView v-if="selectedCommentToShowItsDetails" :comment="selectedCommentToShowItsDetails" isDetails />

    <div v-else v-for="comment in comments" :key="comment.id" @click="clickToSelectACommentToShowItsDetails(comment)"
        class="hover:cursor-pointer">
        <CommentView :comment="comment" />
    </div>

</template>
