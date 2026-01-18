<?php

namespace Integra\Domain\Integration\MindBox\Operation\UpdateBonusStatus\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class UpdateBonusStatusDTO extends AbstractDTO
{
    public function __construct(
        public readonly string      $executionDateTimeUtc,
        public readonly string      $orderLinesStatus,
        public readonly OrderDTO    $order
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return  [
            'executionDateTimeUtc',
            'orderLinesStatus',
            'order'
        ];
    }
}