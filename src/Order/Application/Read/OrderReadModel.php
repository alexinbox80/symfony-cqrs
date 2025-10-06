<?php

namespace App\Order\Application\Read;

final class OrderReadModel
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $userId,
        public readonly float  $amount,
        public readonly string $status,
    ) {}

    public function toArray(): array
    {
        return [
            'orderId' => $this->orderId,
            'userId'  => $this->userId,
            'amount'  => $this->amount,
            'status'  => $this->status,
        ];
    }
}
