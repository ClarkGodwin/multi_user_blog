export type Article = {
    id : number;
    user_id : number;
    title : string;
    content : string;
    status : ArticleStatusEnum;
}

export enum ArticleStatusEnum {
    Craft = 'craft',
    Published = 'published',
    Archived = 'archived',
}
