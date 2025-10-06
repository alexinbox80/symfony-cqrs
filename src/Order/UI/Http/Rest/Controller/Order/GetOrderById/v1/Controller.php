<?php

namespace App\Order\UI\Http\Rest\Controller\Order\GetOrderById\v1;

use App\Order\Application\Query\GetOrderQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class Controller extends AbstractController
{
    use HandleTrait;

    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly MessageBusInterface $queryBus,
    ){
        $this->messageBus = $this->queryBus; // для HandleTrait
    }

    #[Route('/v1/orders/{id}', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        $data = $this->handle(new GetOrderQuery($id));
        return $this->json($data);
    }
}
