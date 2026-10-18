import { Article } from "@/types/article"
import { InputItem } from "@/types/input"

export function getArticleFormInputItem(isUpdate?: boolean, article?: Article): InputItem[] {
    const updateOnlyItemHiddenId: InputItem[] = isUpdate
        ? [{
            id: 4,
            type: {
                kind: 'InputType',
                type: 'hidden',
            },
            name: 'id',
            required: false,
            value: article!.id,
        }]
        : [];

    const inputItems: InputItem[] = [
        {
            id: 1,
            label: 'Title',
            type: {
                kind: 'InputType',
                type: 'text'
            },
            name: 'title',
            required: true,
            ...(isUpdate ? { value: article!.title } : {})
        },
        {
            id: 2,
            label: 'Content',
            type: {
                kind: 'TextareaType',
                type: 'textarea'
            },
            name: 'content',
            required: true,
            ...(isUpdate ? { value: article!.content } : {})
        },
        {
            id: 3,
            label: 'Save to Published or Craft',
            type: {
                kind: 'SelectType',
                type: 'select',
                options: ['Published', 'Craft']
            },
            name: 'status',
            required: true,
            ...(isUpdate ? { value: article!.status } : {})
        },

        ...updateOnlyItemHiddenId
    ]

    return inputItems
}
