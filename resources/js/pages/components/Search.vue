<script setup lang="ts">
import { Search } from '@lucide/vue';

import { Button } from '@/components/ui/button'
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

</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Search class="text-dark-surface-300 dark:text-surface-300 hover:cursor-pointer" />
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Edit profile</DialogTitle>
                <DialogDescription>
                    Make changes to your profile here. Click save when you're
                    done.
                </DialogDescription>
            </DialogHeader>
            <DialogFooter>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
