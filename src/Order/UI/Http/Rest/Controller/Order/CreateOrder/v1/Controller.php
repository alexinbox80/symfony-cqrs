<?php

namespace App\Order\UI\Http\Rest\Controller\Order\CreateOrder\v1;

use App\Order\Application\Command\CreateOrderCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/v1/orders', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $userId = $request->get('userId', 'demo-user');
        $amount = (float)$request->get('amount', 99.0);

        $cmd = new CreateOrderCommand($userId, $amount);
        $this->commandBus->dispatch($cmd);

        return $this->json(['orderId' => $cmd->orderId, 'status' => 'PENDING']);
    }
}
