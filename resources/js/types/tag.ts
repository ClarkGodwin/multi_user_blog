export type Tag = {
    id : number;
    user_id : number;
    name : string;
}

export type ArticleTag = {
    id : number;
    article_id : number;
    tag_id : number;
}
