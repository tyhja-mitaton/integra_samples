<?php

namespace Integra\Domain\Integration\MindBox\Operation\UpdateDepositStatus\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class UpdateDepositStatusDTO extends AbstractDTO
{
    public function __construct(
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
        return [
            'orderLinesStatus',
            'order'
        ];
    }
}