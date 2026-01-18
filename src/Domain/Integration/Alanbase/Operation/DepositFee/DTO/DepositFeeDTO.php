<?php

namespace Integra\Domain\Integration\Alanbase\Operation\DepositFee\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class DepositFeeDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $clickId,
        public readonly string $goal,
        public readonly float $value,
        public readonly string $currency,
        public readonly string $status,
        public readonly int $tid,
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
            'value',
            'currency',
            'status',
            'tid',
            'userId'   => 'custom1',
            'datetime'
        ];
    }
}