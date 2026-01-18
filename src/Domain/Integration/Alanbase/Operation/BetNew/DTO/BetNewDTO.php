<?php

namespace Integra\Domain\Integration\Alanbase\Operation\BetNew\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class BetNewDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $clickId,
        public readonly string $goal,
        public readonly string $status,
        public readonly float $value,
        public readonly string $currency,
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
            'datetime'
        ];
    }
}