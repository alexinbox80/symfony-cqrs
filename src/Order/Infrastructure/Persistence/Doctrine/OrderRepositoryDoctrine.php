<?php

namespace App\Order\Infrastructure\Persistence\Doctrine;

use App\Order\Domain\Model\Order;
use App\Order\Domain\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;

final class OrderRepositoryDoctrine implements OrderRepository
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function save(Order $order): void
    {
        $entity = new OrderOrmEntity();
        $entity->id     = $order->getId();
        $entity->userId = $order->getUserId();
        $entity->amount = $order->getAmount();
        $entity->status = 'PENDING';

        $this->em->persist($entity);
        $this->em->flush();
    }
}
