<script setup lang="ts">
import { create } from '@/routes/article';

</script>

<template>
    <div class="w-fit mx-auto flex flex-col justify-center items-center gap-articles-notAnAuthor-gap min-h-[90%] text-articles-notAnAuthor">
        <div>
            It seems like you've never created an article
        </div>
        <Link :href="create()">
        <Button class="w-fit hover:cursor-pointer">
            Create an article
        </Button>
        </Link>
    </div>
</template>
