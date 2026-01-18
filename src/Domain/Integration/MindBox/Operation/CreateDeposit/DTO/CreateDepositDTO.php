<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateDeposit\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class CreateDepositDTO extends AbstractDTO
{
    public function __construct(
        public readonly string      $executionDateTimeUtc,
        public readonly CustomerDTO $customer,
        public readonly OrderDTO    $order
    )
    {
    }

    protected function fields(): array
    {
        return [
            'executionDateTimeUtc',
            'customer',
            'order'
        ];
    }
}