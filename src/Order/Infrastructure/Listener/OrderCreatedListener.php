<?php

namespace App\Order\Infrastructure\Listener;

use App\Order\Domain\Event\OrderCreated;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'event_bus', fromTransport: 'async')]
final class OrderCreatedListener
{
    public function __construct(private readonly LoggerInterface $logger) {}

    public function __invoke(OrderCreated $event): void
    {
        $this->logger->info('OrderCreated consumed (async)', ['orderId' => $event->orderId]);
    }
}
