<script setup lang="ts">
defineProps<{
    type? : "button" | "submit" | "reset"
}>()
</script>

<template>
    <button :type="type" class="bg-dark-surface-100 dark:bg-surface-100 text-surface-100 dark:text-dark-surface-100 font-bold rounded-lg p-2.5">
        <slot></slot>
    </button>
</template>
