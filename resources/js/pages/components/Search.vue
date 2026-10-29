<script setup lang="ts">
import { Search } from '@lucide/vue';
import {
    Dialog,
    DialogContent,
    DialogTrigger,
} from '@/components/ui/dialog'

import { SearchIcon } from '@lucide/vue'
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group'

</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Search class="text-dark-surface-300 dark:text-surface-300 hover:cursor-pointer size-search-icon" />
        </DialogTrigger>
        <DialogContent :show-close-button="false" class="w-dialog-content top-4 translate-y-0">
            <InputGroup>
                <InputGroupInput placeholder="Search..." />
                <InputGroupAddon>
                    <SearchIcon />
                </InputGroupAddon>
            </InputGroup>
        </DialogContent>
    </Dialog>
</template>
