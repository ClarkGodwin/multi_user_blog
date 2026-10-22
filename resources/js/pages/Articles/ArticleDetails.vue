<script setup lang="ts">
import { Head, router, usePage, } from '@inertiajs/vue3';
import { User } from '@/types/auth.js';
import { Article } from '@/types/article.js';
import ArticleView from '../components/ArticleView.vue';
import CommentView from '../components/CommentView.vue';
import { Comment } from '@/types/comment.js';
import { computed, ref } from 'vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Details of an article',
            },
        ],
    },
});

const props = defineProps<{
    auth: {
        user: User
    }
    userAuthor: User
    article: Article
    comments?: Comment[]
    canTheAuthenticatedUserUpdateTheArticle: boolean
    canTheAuthenticatedUserDeleteTheArticle: boolean
}>()

const page = usePage()

/**
 * Instead of a link on each comment which will imply to pass by the server to display the details, i decided to handle that locally, when this variable is null, the overview will be displayed, if it contains a comment, its details will  be displayed
 */
const selectedCommentToShowItsDetails = computed(() => {
    const url = new URL(page.url, 'http://inertia.local');
    const commentId = url.searchParams.get('comment');

    if (commentId === null) {
        return null;
    }

    return props.comments?.find(
        comment => comment.id === Number(commentId),
    ) ?? null;
});

function clickToSelectACommentToShowItsDetails(comment: Comment) {
    const url = new URL(page.url, 'http://inertia.local');

    url.searchParams.set('comment', String(comment.id)); //when a comment is clicked, a new url is created with the id of the comment as a parametere

    //the goal is to create another history state so that the user can go back and forth between the overview of all the comments and the details of one comment without any call to the server. Everything stays local
    router.push({
        url: `${url.pathname}${url.search}${url.hash}`,
        preserveState: true,
        preserveScroll: true,
    });
}


</script>

<template>

    <Head title="Details of an Article" />

    <ArticleView :article="article" :user="userAuthor" isDetails
        :canTheAuthenticatedUserDeleteTheArticle="canTheAuthenticatedUserDeleteTheArticle"
        :canTheAuthenticatedUserUpdateTheArticle="canTheAuthenticatedUserUpdateTheArticle" />

    <CommentView v-if="selectedCommentToShowItsDetails" :comment="selectedCommentToShowItsDetails" isDetails />

    <div v-else v-for="comment in comments" :key="comment.id" @click="clickToSelectACommentToShowItsDetails(comment)"
        class="hover:cursor-pointer">
        <CommentView :comment="comment" />
    </div>

</template>
