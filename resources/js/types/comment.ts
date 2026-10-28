import { User } from "./auth";

export type Comment = {
    id : number;
    user_id : number;
    article_id : number;
    content : string;
    user : User;
    created_at : string;
    updated_at : string;
}

export type CommentPermissions = {
    canTheAuthenticatedUserUpdateTheComment: boolean;
    canTheAuthenticatedUserDeleteTheComment: boolean;
}

export type CommentsPaginated = {
    current_page: number;
    data: Comment[];
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
