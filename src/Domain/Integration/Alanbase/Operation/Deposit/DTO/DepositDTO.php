<?php

namespace Integra\Domain\Integration\Alanbase\Operation\Deposit\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class DepositDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $clickId,
        public readonly string $event,
        public readonly float $value,
        public readonly string $currency,
        public readonly int    $userId,
        public readonly int    $payId,
        public readonly int    $datetime
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'clickId'  => 'click_id',
            'event',
            'value',
            'currency',
            'userId'   => 'custom1',
            'payId'    => 'custom2',
            'datetime'
        ];
    }
}