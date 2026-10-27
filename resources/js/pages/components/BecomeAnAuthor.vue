<script setup lang="ts">
import { toAuthor } from '@/routes';

</script>

<template>
    <div class="w-fit mx-auto flex flex-col justify-center items-center gap-articles-notAnAuthor-gap min-h-[90%] text-articles-notAnAuthor">
        <div>It seems like you're trying to create articles without being registered as an author</div>
        <Link :href="toAuthor()" method="post">
            <Button class="w-fit hover:cursor-pointer">
                Become an author
            </Button>
        </Link>
    </div>
</template>
