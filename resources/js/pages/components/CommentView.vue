<script setup lang="ts">
import { Comment } from '@/types/comment';
import { destroy } from '@/routes/comment';
import { Link } from '@inertiajs/vue3';
import {  PencilLine, Trash2 } from '@lucide/vue';

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
import { truncatedContent } from '@/utils/truncatedContent';

defineProps<{
    comment: Comment
    isDetails?: boolean
    canTheAuthenticatedUserUpdateTheComment?: boolean
    canTheAuthenticatedUserDeleteTheComment?: boolean
}>()
</script>

<template>
    <section class=" w-comment-view mx-auto py-4 flex flex-col gap-3 border-b">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                <img :src="comment.user.avatar ? comment.user.avatar : '/storage/images/avatars/default.jpg'" alt=""
                    class="rounded-sm sm:rounded-lg w-article-view-avatar">

                <span class="text-article-view-name">{{ comment.user.name }}</span>
            </div>

            <div v-if="isDetails" class="flex gap-2.5 **:size-(--text-article-view-content)">
                <div v-if="canTheAuthenticatedUserUpdateTheComment">
                    <PencilLine class="text-green-500" @click="$emit('editAComment')"/>
                </div>

                <Dialog>
                    <DialogTrigger>
                        <Trash2 v-if="canTheAuthenticatedUserDeleteTheComment" class="text-red-500 hover:cursor-pointer" />
                    </DialogTrigger>
                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle>Are you absolutely sure?</DialogTitle>
                            <DialogDescription>
                                This action cannot be undone. This will permanently delete your comment
                                and remove your data from our servers.
                            </DialogDescription>
                        </DialogHeader>

                        <DialogFooter>
                            <DialogClose as-child>
                                <Button variant="outline">
                                    No
                                </Button>
                            </DialogClose>
                            <Link :href="destroy(comment.id)" method="delete" v-if="canTheAuthenticatedUserDeleteTheComment">
                                <Button class="w-full">
                                    Yes
                                </Button>
                            </Link>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </div>
        </div>

        <div class="text-article-view-content whitespace-pre-wrap">
            {{ truncatedContent(isDetails, comment.content) }}
        </div>

        <div class="text-article-view-name text-dark-surface-400 dark:text-surface-400">
            {{ formattedRelativeDate(comment.created_at) }}
        </div>
    </section>
</template>
