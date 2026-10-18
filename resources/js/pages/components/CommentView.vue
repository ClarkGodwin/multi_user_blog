<script setup lang="ts">
import { Comment } from '@/types/comment';

defineProps<{
    comment: Comment
}>()
</script>

<template>
    {{ comment.content }}
</template>
