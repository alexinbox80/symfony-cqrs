<?php

namespace App\Order\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'orders')]
class OrderOrmEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    public string $id;

    #[ORM\Column(type: 'string')]
    public string $userId;

    #[ORM\Column(type: 'float')]
    public float $amount;

    #[ORM\Column(type: 'string')]
    public string $status = 'PENDING';
}
