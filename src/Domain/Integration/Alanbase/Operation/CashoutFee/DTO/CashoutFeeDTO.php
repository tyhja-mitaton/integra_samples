<?php

namespace Integra\Domain\Integration\Alanbase\Operation\CashoutFee\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class CashoutFeeDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $clickId,
        public readonly string $goal,
        public readonly string $status,
        public readonly float $value,
        public readonly string $currency,
        public readonly int    $tid,
        public readonly int    $userId,
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
            'goal',
            'status',
            'value',
            'currency',
            'tid',
            'userId'   => 'custom1',
            'datetime'
        ];
    }
}