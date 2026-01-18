<?php

namespace Integra\Domain\Integration\Promo\Operation\Replenishment\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class ReplenishmentPromoDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly int $paymentId,
        public readonly int $typeId
    ) {}

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'data' => ['user_id', 'payment_id'],
            'type_id',
        ];
    }
}