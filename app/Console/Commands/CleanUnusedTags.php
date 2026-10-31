<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:clean-unused-tags')]
#[Description('To automatically monthly delete tags that are not linked to an article anymore')]
class CleanUnusedTags extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
