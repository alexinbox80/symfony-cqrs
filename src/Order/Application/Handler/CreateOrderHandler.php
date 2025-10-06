<?php

namespace App\Order\Application\Handler;

use App\Order\Application\Command\CreateOrderCommand;
use App\Order\Domain\Model\Order;
use App\Order\Domain\Repository\OrderRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler(bus: 'command_bus')]
final class CreateOrderHandler
{
    public function __construct(
        private readonly OrderRepository $orders,
        private readonly MessageBusInterface $eventBus,
        private readonly LoggerInterface $logger,
    ) {}

    public function __invoke(CreateOrderCommand $cmd): void
    {
        // 1) создаем агрегат
        $order = Order::create($cmd->orderId, $cmd->userId, $cmd->amount);

        // 2) сохраняем
        $this->orders->save($order);
        $this->logger->info('Order persisted', ['orderId' => $order->getId()]);

        // 3) публикуем его доменные события
        foreach ($order->releaseEvents() as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
