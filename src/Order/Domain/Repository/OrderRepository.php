<?php

namespace App\Order\Domain\Repository;

use App\Order\Domain\Model\Order;

interface OrderRepository
{
    public function save(Order $order): void;
}
