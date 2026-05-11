<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Services\CartService;

#[Signature('app:clean-guest-carts')]
#[Description('Command description')]
class CleanGuestCarts extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(CartService $cartService)
    {
        $cartService->deleteOldGuestCarts();
    }
}
