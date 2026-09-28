export type InputItem = {
    id : number
    label? : string
    type : InputType
    name : string
    value? : string | number
    required : boolean
}

export type InputType = 'text' | 'textarea' | 'email' | 'password' | 'hidden' | 'number' | 'select'
