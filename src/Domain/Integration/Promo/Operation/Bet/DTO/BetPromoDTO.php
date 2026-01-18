<?php

namespace Integra\Domain\Integration\Promo\Operation\Bet\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class BetPromoDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly int $orderNumber,
        public readonly int $typeId
    ) {}

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'data' => ['user_id', 'order_number'],
            'type_id',
        ];
    }
}