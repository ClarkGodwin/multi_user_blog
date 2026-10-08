<script setup lang="ts">
import { User } from '@/types';
import { Article } from '@/types/article';

defineProps<{
    user : User
    article : Article
}>()

</script>

<template>
    {{ article.title }}
    {{ user.name }}
</template>
