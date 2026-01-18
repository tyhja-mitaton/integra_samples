<?php

namespace Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;


class ChangeBetStatusDTO extends AbstractDTO
{
    public function __construct(
        public readonly string      $orderLinesStatus,
        public readonly OrderDTO    $order,
        public readonly string      $executionDateTimeUtc,
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'orderLinesStatus',
            'order',
            'executionDateTimeUtc'
        ];
    }
}