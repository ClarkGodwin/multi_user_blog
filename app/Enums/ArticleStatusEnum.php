<?php

namespace App\Enums;

enum ArticleStatusEnum : string
{
    case Craft = "crafr";
    case Published = "published";
    case Archived = "archived";
}
