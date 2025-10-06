<?php

namespace App\Order\Domain\Event;

final class OrderCreated
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $userId,
        public readonly float $amount
    ) {}
}
