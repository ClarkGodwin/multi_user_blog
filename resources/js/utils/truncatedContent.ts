/**
 * This function returns a truncated content depending on a boolean variable provided in the parameter and , eventually, the maximum number of characters and maximum number of lines, the default value will be 500 for the maxLenght and 5 for the maxLines but you can modify it as you want
 *
 * Picture a long text, well this function will return the full text if the boolean is true or the truncated content, which means the 500 characters(or any  other number you provided) with '...' to indicate that there's more, if the variable is true
 */

export function truncatedContent(isDetailed: boolean, content: string, maxLength: number = 500, maxLines: number = 5): string {
    if (content.length <= maxLength) {
        if (!isDetailed) {
            /**
             * This variable split the content parameter with the line breaks used in an operating system
             * split(/\r?\n/) handles both Unix (\n) and Windows (\r\n) line breaks
             */
            const arrayOfContentDividedByLine = content.split(/\r?\n/);

            /**
             * If we have more than the maxLines in the parameter, then we only show the first maxLines lines
             */
            if (arrayOfContentDividedByLine.length > maxLines) {
                return arrayOfContentDividedByLine.slice(0, maxLines).join('\n') + '...';
            }
        }

        return content;
    }
    else {
        return isDetailed ? content : content.substring(0, maxLength) + '...';
    }
}

