<?php

namespace App\Enums;

//If any of theses values is changes, the corresponding values have also to be changed  in auth.ts

enum UserRoleEnum : string
{
    case Reader = "reader";
    case Author = "author";
    case Admin = "admin";
    case Suspended = "suspended";
    case Banned = "banned";
}
