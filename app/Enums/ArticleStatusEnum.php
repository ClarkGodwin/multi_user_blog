<?php

namespace App\Enums;

enum ArticleStatusEnum : string
{
    case Craft = "craft";
    case Published = "published";
    case Archived = "archived";
}
