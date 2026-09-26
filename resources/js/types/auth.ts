export type User = {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    role: UserRole;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};

export enum UserRole {
    Reader = 'reader',
    Author = 'author',
    Admin = 'admin',
    Suspended = 'suspended',
    Banned = 'banned',
}
