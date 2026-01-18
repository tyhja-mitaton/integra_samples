<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateBonus\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class CreateBonusDTO extends AbstractDTO
{
    public function __construct(
        public readonly string      $executionDateTimeUtc,
        public readonly CustomerDTO $customer,
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
            'executionDateTimeUtc',
            'customer',
            'order',
        ];
    }
}