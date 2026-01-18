<?php

namespace Integra\Domain\Integration\Alanbase\Operation\Bonus\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class BonusDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $clickId,
        public readonly string $event,
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
            'event',
            'value',
            'currency',
            'datetime'
        ];
    }
}