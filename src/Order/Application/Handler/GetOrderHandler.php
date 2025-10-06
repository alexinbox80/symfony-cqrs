<?php

namespace App\Order\Application\Handler;

use App\Order\Application\Query\GetOrderQuery;
use App\Order\Application\Read\OrderReadMapper;
use App\Order\Domain\Repository\OrderReadRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query_bus')]
final class GetOrderHandler
{
    public function __construct(
        private readonly OrderReadRepository $orders
    ) {}

    public function __invoke(GetOrderQuery $query): array
    {
        $row = $this->orders->findById($query->orderId);

        if (!$row) {
            return ['error' => 'ORDER_NOT_FOUND', 'orderId' => $query->orderId];
        }

        $dto = OrderReadMapper::fromRow($row, $query->orderId);

        return $dto->toArray();
    }
}
