<?php

namespace Integra\Domain\Integration\Adjust\Operation\FirstTimeDeposit\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class CallbackParamsDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly string $payId,
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
            'payId' => 'custom2',
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