<?php

namespace App\Order\Application\Read;

final class OrderReadMapper
{
    public static function fromRow(array $row, string $fallbackId): OrderReadModel
    {
        return new OrderReadModel(
            orderId: (string)($row['id'] ?? $fallbackId),
            userId:  (string)($row['user_id'] ?? ($row['userId'] ?? '')),
            amount:  (float)($row['amount'] ?? 0),
            status:  (string)($row['status'] ?? 'UNKNOWN'),
        );
    }
}
