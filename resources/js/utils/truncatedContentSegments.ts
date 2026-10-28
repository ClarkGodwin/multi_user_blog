/**
 * This file contains a function that separates each part of a long text between  a casual segment of a text and a tag(that starts with a '#') so that it can be displayed with a particular style
 */

import { truncatedContent } from "./truncatedContent"

export type ContentSegment = {
    text: string
    isTag: boolean
}

export function truncatedContentSegments(
    isDetailed: boolean,
    content: string,
    maxLength: number = 500,
    maxLines: number = 5
): ContentSegment[] {
    const text = truncatedContent(isDetailed, content, maxLength, maxLines)

    return text
        /**
         * check createTagsFromAnArticle in articleController.php to have a better understanding of what is  a tag
         * it splits the long text between normal segments of a text and tags using '#'
         */
        .split(/(#[^\s#]+)/gu)
        .filter(Boolean)
        .map((segment) => ({
            text: segment,
            isTag: segment.startsWith('#'),
        }))
}
