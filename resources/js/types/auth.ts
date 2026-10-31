export type User = {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    role: UserRoleEnum;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};

//If any of theses values is changes, the corresponding values have also to be changed  in UserRoleEnum.php
export enum UserRoleEnum {
    Reader = 'reader',
    Author = 'author',
    Admin = 'admin',
}
