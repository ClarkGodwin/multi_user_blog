/**
 * This file contains a function that separates each part of a long text between  a casual word and a tag(that starts with a '#') so that it can be displayed with a particular style
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
        .split(/(#[^\s#]+)/gu)
        .filter(Boolean)
        .map((segment) => ({
            text: segment,
            isTag: segment.startsWith('#'),
        }))
}
