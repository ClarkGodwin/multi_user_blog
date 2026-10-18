<script setup lang="ts">
import { edit } from '@/routes/article';
import { User } from '@/types';
import { Article } from '@/types/article';
import { Link } from '@inertiajs/vue3';
import { PencilLine, Trash2 } from '@lucide/vue';

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

defineProps<{
    user: User
    article: Article
    isDetails?: boolean
}>()

</script>

<template>
    <section class=" w-article-view mx-auto py-4 flex flex-col gap-3 border-b">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                <img :src="user.avatar ? user.avatar : '/storage/images/avatars/default.jpg'" alt=""
                    class="rounded-sm sm:rounded-lg w-article-view-avatar">

                <span class="text-article-view-name">{{ user.name }}</span>
            </div>

            <div v-if="isDetails" class="flex gap-2.5 **:size-(--text-article-view-content)">
                <Link :href="edit(article.id)">
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
                                This action cannot be undone. This will permanently delete your article
                                and remove your data from our servers.
                            </DialogDescription>
                        </DialogHeader>

                        <DialogFooter>
                            <DialogClose as-child>
                                <Button variant="outline">
                                    Cancel
                                </Button>
                            </DialogClose>
                            <Button>
                                Save changes
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </div>
        </div>

        <div class="text-center text-article-view-title font-bold">{{ article.title }}</div>

        <div class="text-article-view-content">
            {{ isDetails ? article.content : article.content.substring(0, 500) + '...' }}
        </div>
    </section>
</template>
