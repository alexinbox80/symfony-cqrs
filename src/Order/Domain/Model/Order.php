<?php
// это модель, сущность в репозитории
namespace App\Order\Domain\Model;

use App\Order\Domain\Event\OrderCreated;

final class Order
{
    /** @var object[] */
    private array $recordedEvents = [];

    private function __construct(
        private string $id,
        private string $userId,
        private float $amount,
        private string $status = 'PENDING',
    ) {}

    public static function create(string $id, string $userId, float $amount): self
    {
        $self = new self($id, $userId, $amount);
        $self->recordThat(new OrderCreated($id, $userId, $amount));

        return $self;
    }

    /** @return object[] */
    public function releaseEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];

        return $events;
    }

    private function recordThat(object $event): void
    {
        $this->recordedEvents[] = $event;
    }

    // Геттеры для маппинга/чтения
    public function getId(): string { return $this->id; }
    public function getUserId(): string { return $this->userId; }
    public function getAmount(): float { return $this->amount; }
    public function getStatus(): string { return $this->status; }
}
