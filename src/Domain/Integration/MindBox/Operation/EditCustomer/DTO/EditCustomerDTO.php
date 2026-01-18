<?php

namespace Integra\Domain\Integration\MindBox\Operation\EditCustomer\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

final class EditCustomerDTO extends AbstractDTO
{
    public function __construct(
        public readonly string      $executionDateTimeUtc,
        public readonly CustomerDTO $customer,
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
        ];
    }
}