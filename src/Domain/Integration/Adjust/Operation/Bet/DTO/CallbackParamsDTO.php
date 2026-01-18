<?php

namespace Integra\Domain\Integration\Adjust\Operation\Bet\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class CallbackParamsDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly string $status,
        public readonly string $goal,
        public readonly string $amount,
        public readonly string $currency,
        public readonly string $datetime,
        public readonly string $partnerId,
        public readonly string $offerId,
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'userId' => 'custom1',
            'status',
            'goal',
            'amount' => 'value',
            'currency',
            'datetime',
            'partnerId' => 'partner_id',
            'offerId' => 'offer_id',
        ];
    }
}