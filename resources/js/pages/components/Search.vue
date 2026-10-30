<script setup lang="ts">
import { Search } from '@lucide/vue';
import {
    Dialog,
    DialogContent,
    DialogTrigger,
} from '@/components/ui/dialog'

import { SearchIcon } from '@lucide/vue'
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group'

import { onBeforeUnmount, ref, watch } from 'vue';

/**
 * The type of the data returned by either the search or the tagSuggestion function in ArticleController.php
 *
 * The return type of datas from that controller is json
 */
type SearchArticle = {
    id: number;
    title: string;
};

const query = ref('');
const selectedTag = ref<string | null>(null);
const articles = ref<SearchArticle[]>([]);
const tagSuggestions = ref<string[]>([]);
const loading = ref(false);
const searchError = ref('');

/**
 *To wait and see if the user stopped typing for 250ms before lauching the request
 */
let debounceTimer: ReturnType<typeof setTimeout> | undefined;

/**
 *To be able to stop  a precedent request which became obsolete in case the user typed another character after that 250ms wait
 */
let activeController: AbortController | undefined;



/**
 * Since Typescript doesn't check the type of datas returned by the controller, the following functions will do that job before they're used in a function
 *
 */

function isDataEnvelope(value: unknown): value is { data: unknown[] } {
    return (
        typeof value === 'object' &&
        value !== null &&
        'data' in value &&
        Array.isArray(value.data)
    );
}

function isSearchArticle(value: unknown): value is SearchArticle {
    return (
        typeof value === 'object' &&
        value !== null &&
        'id' in value &&
        typeof value.id === 'number' &&
        'title' in value &&
        typeof value.title === 'string'
    );
}

function isString(value: unknown): value is string {
    return typeof value === 'string';
}

async function fetchList<T>(
    url: string,
    signal: AbortSignal,
    isItem: (value: unknown) => value is T,
): Promise<T[]> {
    const response = await fetch(url, {
        headers: { Accept: 'application/json' },
        signal,
    });

    if (!response.ok) {
        throw new Error(`La recherche a échoué (${response.status}).`);
    }

    const payload: unknown = await response.json();

    if (!isDataEnvelope(payload)) {
        throw new Error('La réponse de recherche est invalide.');
    }

    const items: T[] = [];

    for (const item of payload.data) {
        if (!isItem(item)) {
            throw new Error('Un résultat de recherche est invalide.');
        }

        items.push(item);
    }

    return items;
}

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
