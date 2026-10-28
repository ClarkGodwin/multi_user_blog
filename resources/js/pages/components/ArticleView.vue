<script setup lang="ts">
import { edit, destroy } from '@/routes/article';
import { User } from '@/types';
import { Article } from '@/types/article';
import { Link } from '@inertiajs/vue3';
import { MessageSquare, PencilLine, Trash2 } from '@lucide/vue';

import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog'

import { Button } from '@/components/ui/button'
import { formattedRelativeDate } from '@/utils/formattedRelativeDate';
import { truncatedContentSegments } from '@/utils/truncatedContentSegments';

defineProps<{
    user: User
    article: Article

    isDetails?: boolean //just to know if the modification links can be displayed in case the user has the permissions

    canTheAuthenticatedUserUpdateTheArticle?: boolean
    canTheAuthenticatedUserDeleteTheArticle?: boolean
}>()

</script>

<template>
    <section class=" w-article-view mx-auto py-4 flex flex-col gap-3 border-b mb-5">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                <img :src="user.avatar ? user.avatar : '/storage/images/avatars/default.jpg'" alt=""
                    class="rounded-sm sm:rounded-lg w-article-view-avatar">

                <span class="text-article-view-name">{{ user.name }}</span>
            </div>

            <!-- Modification links -->
            <div v-if="isDetails" class="flex gap-2.5 **:size-(--text-article-view-content)">
                <!-- To edit -->
                <Link :href="edit(article)" v-if="canTheAuthenticatedUserUpdateTheArticle">
                    <PencilLine class="text-green-500" />
                </Link>

                <Dialog>
                    <DialogTrigger class="hover:cursor-pointer">
                        <Trash2 class="text-red-500" v-if="canTheAuthenticatedUserDeleteTheArticle" />
                    </DialogTrigger>
                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle>Are you absolutely sure?</DialogTitle>
                            <DialogDescription>
                                This action cannot be undone. This will permanently delete your article
                                and remove your data from our servers.
                            </DialogDescription>
                        </DialogHeader>

                        <DialogFooter>
                            <DialogClose as-child>
                                <Button variant="outline">
                                    No
                                </Button>
                            </DialogClose>
                            <Link :href="destroy(article)" method="delete" class="hover:cursor-pointer">
                                <!-- To delete -->
                                <Button class="w-full" v-if="canTheAuthenticatedUserDeleteTheArticle">
                                    Yes
                                </Button>
                            </Link>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </div>
        </div>

        <div class="text-center text-article-view-title font-bold">{{ article.title }}</div>

        <div class="text-article-view-content whitespace-pre-wrap">
            <span v-for="(segment, index) in truncatedContentSegments(isDetails, article.content)" :key="index"
                :class="{ 'text-blue-600 dark:text-blue-400': segment.isTag }">
                {{ segment.text }}
            </span>

        </div>

        <!-- To show the number of  comments and when the article was published -->
        <div class="text-article-view-name flex items-center gap-3 text-dark-surface-400 dark:text-surface-400">
            <div class="flex gap-1 items-center">
                <MessageSquare class="size-(--text-article-view-name)" />
                {{ article.comments_count }}
            </div>
            <div>{{ formattedRelativeDate(article.updated_at) }}</div>
        </div>

    </section>
</template>
