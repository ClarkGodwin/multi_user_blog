<script setup lang="ts">
import { Comment } from '@/types/comment';
import { edit, destroy } from '@/routes/article';
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

defineProps<{
    comment: Comment
    isDetails?: boolean
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
                <Link :href="edit(comment.id)">
                    <PencilLine class="text-green-500" />
                </Link>

                <Dialog>
                    <DialogTrigger>
                        <Trash2 class="text-red-500" />
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
                            <Link :href="destroy(comment.id)" method="delete">
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
            {{ isDetails ? comment.content : comment.content.substring(0, 500) + '...' }}
        </div>

        <div class="text-article-view-name text-dark-surface-400 dark:text-surface-400">
            {{ formattedRelativeDate(comment.created_at) }}
        </div>
    </section>
</template>
