<?php

namespace App\Order\Domain\Repository;

interface OrderReadRepository
{
    public function findById(string $id): ?array;
}
