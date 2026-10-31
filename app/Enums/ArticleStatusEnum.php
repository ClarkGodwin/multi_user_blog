<?php

namespace App\Enums;

//If any of theses values is changes, the corresponding values have also to be changed  in article.ts

enum ArticleStatusEnum : string
{
    case Craft = "craft";
    case Published = "published";
    case Archived = "archived";
}
