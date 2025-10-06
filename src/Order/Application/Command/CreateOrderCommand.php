<?php

namespace App\Order\Application\Command;

use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateOrderCommand
{
    #[Assert\NotBlank]
    public string $orderId;

    public function __construct(
        #[Assert\NotBlank] public readonly string $userId,
        #[Assert\Positive] public readonly float $amount
    ) {
        $this->orderId = (string)new Ulid();
    }
}
