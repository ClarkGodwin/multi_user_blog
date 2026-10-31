<script setup lang="ts">
import { Search } from '@lucide/vue';
import {
    Dialog,
    DialogContent,
    DialogTrigger,
} from '@/components/ui/dialog'

import { SearchIcon } from '@lucide/vue'
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group'

import { computed, onBeforeUnmount, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { ArticleStatusEnum } from '@/types/article';

const page = usePage()

/**
 * To extract the status that'll be used in the search or the tag suggestion depending on which page the user is
 */
const currentStatus = computed(() => {
    /**
     * To get the url we're on without the parameters
     * It's splitted using '?', to separate the url from the parameters and then we take the first element
     */
    const path = page.url.split('?')[0];

    /**
     * To get the array of the words used to make the url without the '/' and filtered with Boolean to get rid of the empty strings
     */
    const segments = path.split('/').filter(Boolean);

    if (segments.includes('craft')) return ArticleStatusEnum.Craft;
    if (segments.includes('archived')) return ArticleStatusEnum.Archived;
    if (segments.includes('published')) return ArticleStatusEnum.Published;
    if (segments.includes('home')) return ArticleStatusEnum.Published;

    return ArticleStatusEnum.Published

});

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


/**
 * To asynchronoulsy fetch the datas from the url for the search or the tagSuggestion
 */
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
        throw new Error(`The search failed (${response.status}).`);
    }

    const payload: unknown = await response.json();

    //If the answer is not an object
    if (!isDataEnvelope(payload)) {
        throw new Error('The search results are invalid.');
    }

    const items: T[] = [];

    for (const item of payload.data) {
        /**
         * If an answer doesn't correspond to the type T
         *
         * For example, if we make the fetch request with T as SearchArticle, isItem will have the type SearchArticle and if the item doesn't correspond to that type, throw an error,  and if it corresponds, well, the item will be pushed inside items
         */
        if (!isItem(item)) {
            throw new Error('A search result is invalid.');
        }

        items.push(item);
    }

    return items;
}



watch([query, selectedTag], ([rawQuery, chosenTag]) => {
    if (debounceTimer) {
        //to not send a request for a precedent input in the search input by the user
        clearTimeout(debounceTimer);
    }

    //since this block will be executed whenever either of the two values in watch changes, we need to abort any precedent request that was on
    activeController?.abort();

    const value = rawQuery.trim();

    /**
     * True when we have a selected tag
     * The selected tag is the one chosen in the tag suggestion that will be displayed as the user is typing a word starting with '#'
     *
     * so, unless it's a selected tag, this variable will be false
     */
    const isChosenTag = chosenTag !== null && value === `#${chosenTag}`;

    articles.value = [];
    tagSuggestions.value = [];
    searchError.value = '';

    //it stops the request if the search input is empty or just filled with space
    if (value === '') {
        loading.value = false;
        return;
    }

    debounceTimer = setTimeout(async () => {
        const controller = new AbortController();
        activeController = controller;
        loading.value = true;

        try {
            if (isChosenTag) {
                const url = `/article-search?tag=${encodeURIComponent(chosenTag)}`;

                //fetch results if there's a selected tag
                articles.value = await fetchList(
                    url,
                    controller.signal,
                    isSearchArticle,
                );
            } else if (value.startsWith('#')) {
                const tagTerm = value.slice(1);
                const url = `/tag-suggestions?q=${encodeURIComponent(tagTerm)}`;

                //fetch results if there's not a selected tag and the word starts with a '#' so that the tag suggestions can be displayed
                tagSuggestions.value = await fetchList(
                    url,
                    controller.signal,
                    isString,
                );
            } else {
                const url = `/article-search?q=${encodeURIComponent(value)}`;

                //in this case, it's a normal search through the title and the content of one or many articles
                articles.value = await fetchList(
                    url,
                    controller.signal,
                    isSearchArticle,
                );
            }
        } catch {
            //to show the error only if the query wasn't aborted
            if (!controller.signal.aborted) {
                searchError.value =
                    'The search is unavailable.';
            }
        } finally {
            if (activeController === controller) {
                loading.value = false;
                activeController = undefined;
            }
        }
    }, 250);
});

function onQueryInput(value: string) {
    selectedTag.value = null;
    query.value = value;
}

function selectTag(tag: string) {
    selectedTag.value = tag;
    query.value = `#${tag}`;
}

onBeforeUnmount(() => {
    if (debounceTimer) {
        clearTimeout(debounceTimer);
    }

    activeController?.abort();
});

</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Search class="text-dark-surface-300 dark:text-surface-300 hover:cursor-pointer size-search-icon" />
        </DialogTrigger>
        <DialogContent :show-close-button="false" class="w-dialog-content top-4 translate-y-0">
            <InputGroup>
                <InputGroupInput :model-value="query" placeholder="Search an article or enter a #tag..."
                    @update:model-value="onQueryInput" />
                <InputGroupAddon>
                    <SearchIcon />
                </InputGroupAddon>
            </InputGroup>

            <div>
                <p v-if="loading">Search in progress…</p>
                <p v-else-if="searchError" role="alert">{{ searchError }}</p>

                <ul v-else-if="query.trim().startsWith('#') && selectedTag === null">
                    <li v-for="tag in tagSuggestions" :key="tag">
                        <button type="button" @click="selectTag(tag)">
                            #{{ tag }}
                        </button>
                    </li>
                </ul>

                <ul v-else>
                    <li v-for="article in articles" :key="article.id">
                        <a :href="`/article/detail/${article.id}`">
                            {{ article.title }}
                        </a>
                    </li>
                </ul>
            </div>
        </DialogContent>
    </Dialog>
</template>
