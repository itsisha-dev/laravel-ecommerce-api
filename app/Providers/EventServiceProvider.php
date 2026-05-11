<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\CartItemAdded;
use App\Listeners\SendCartAddedNotification;
use App\Events\OrderPlaced;
use App\Listeners\SendOrderConfirmation;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CartItemAdded::class => [
            SendCartAddedNotification::class,
        ],
        OrderPlaced::class => [
            SendOrderConfirmation::class,
        ],
    ];
}