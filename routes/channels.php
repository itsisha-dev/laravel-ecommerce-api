<?php

Broadcast::channel('orders.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Broadcast::channel('cart.{userId}', function ($user, $userId) {
//     return (int) $user->id === (int) $userId;
// });

Broadcast::channel('cart.{sessionId}', function () {
    return true;
});