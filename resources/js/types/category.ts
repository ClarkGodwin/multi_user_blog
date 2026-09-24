export type Category = {
    id : number;
    user_id : number;
    name : string;
}

export type ArticleCategory = {
    id : number;
    article_id : number;
    category_id : number;
}
