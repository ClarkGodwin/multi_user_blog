import { LucideIcon } from "@lucide/vue"

export type InputItem = {
    id : number
    label? : string
    type : InputType | TextareaType | SelectType
    name : string
    value? : string | number
    required : boolean
    icon? : LucideIcon
}

export type InputType = {
    kind : 'InputType'
    option : 'text' | 'email' | 'password' | 'hidden' | 'number'
}

export type TextareaType = {
    kind : 'TextareaType'
    option :'textarea'
}

export type SelectType = {
    kind : 'SelectType'
    option : 'select'
}
