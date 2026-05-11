<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:listen-a-i-results')]
#[Description('Command description')]
class ListenAIResults extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        Redis::subscribe(['ai-results'], function ($message) {
            $data = json_decode($message, true);
            broadcast(new AIProcessedEvent($data));
        });
    }
}
