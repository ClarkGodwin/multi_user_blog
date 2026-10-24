import { User } from "./auth";

export type Comment = {
    id : number;
    user_id : number;
    article_id : number;
    content : string;
    user : User;
    created_at : string;
}

export type CommentPermissions = {
    canTheAuthenticatedUserUpdateTheComment: boolean;
    canTheAuthenticatedUserDeleteTheComment: boolean;
}
