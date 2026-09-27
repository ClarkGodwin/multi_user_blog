export type Article = {
    id : number;
    user_id : number;
    title : string;
    content : string;
    status : ArticleStatusEnum;
}

export type ArticlesPaginated = {
    current_page: number;
    data: Article[];
    first_page_url: string;
    from: number | null;
    last_page: number;
    last_page_url: string;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number | null;
    total: number;
};

export enum ArticleStatusEnum {
    Craft = 'craft',
    Published = 'published',
    Archived = 'archived',
}
