<script setup lang="ts">
import { Form, Head, router, usePage, } from '@inertiajs/vue3';
import { User } from '@/types/auth.js';
import { Article } from '@/types/article.js';
import ArticleView from '../components/ArticleView.vue';
import CommentView from '../components/CommentView.vue';
import { Comment, CommentPermissions } from '@/types/comment.js';
import { computed, ref } from 'vue';
import { details } from '@/routes/comment/index.js';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import Button from '@/components/ui/button/Button.vue';

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
    comment?: Comment
    canTheAuthenticatedUserUpdateTheArticle: boolean
    canTheAuthenticatedUserDeleteTheArticle: boolean
}>()

const canTheAuthenticatedUserUpdateTheComment = ref(false)
const canTheAuthenticatedUserDeleteTheComment = ref(false)

async function loadCommentPermissions(comment: Comment) {
        try {
            const response = await fetch(details.url(comment), {
                headers: {
                    Accept: 'application/json',
                },
            });

            if(response.ok){
                let commentPermissions = await response.json() as CommentPermissions

                canTheAuthenticatedUserUpdateTheComment.value = commentPermissions.canTheAuthenticatedUserUpdateTheComment

                canTheAuthenticatedUserDeleteTheComment.value = commentPermissions.canTheAuthenticatedUserDeleteTheComment
            }
        }
        catch(error){
            console.log(error)
        }
}

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

    let comment = props.comments?.find(
        comment => comment.id === Number(commentId),
    ) ?? null;

    if (comment) {
        loadCommentPermissions(comment)
    }

    return comment
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

    <Form method="post" #default="{ errors: formErrors }" class="w-comment-view mx-auto flex sm:flex-row flex-col items-center justify-between">
        <div class="sm:w-[90%]">
            <Textarea name="content" placeholder="Add a comment"/>
            <div class="text-red-500">{{ formErrors['content'] }}</div>
        </div>
        <Button type="submit" class="font-bold">Submit</Button>
    </Form>

    <CommentView v-if="selectedCommentToShowItsDetails" :comment="selectedCommentToShowItsDetails" isDetails
        :canTheAuthenticatedUserUpdateTheComment="canTheAuthenticatedUserUpdateTheComment"
        :canTheAuthenticatedUserDeleteTheComment="canTheAuthenticatedUserDeleteTheComment" />

    <div :href="details(comment)" v-else v-for="comment in comments" :key="comment.id" class="hover:cursor-pointer"
        @click="clickToSelectACommentToShowItsDetails(comment)">
        <CommentView :comment="comment" />
    </div>

</template>
