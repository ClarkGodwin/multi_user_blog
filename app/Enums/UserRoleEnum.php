<?php

namespace App\Enums;

enum UserRoleEnum : string
{
    case Reader = "reader";
    case Author = "author";
    case Admin = "admin";
    case Suspended = "suspended";
    case Banned = "banned";
}
