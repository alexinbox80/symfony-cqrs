<?php

namespace App\Order\Infrastructure\Persistence\Doctrine;

use App\Order\Domain\Repository\OrderReadRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderReadRepositoryDoctrine implements OrderReadRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function findById(string $id): ?array
    {
        $row = $this->em->getConnection()
            ->fetchAssociative('SELECT id, userId, status, amount FROM orders WHERE id = :id', ['id' => $id]);

        return $row ?: null;
    }
}
