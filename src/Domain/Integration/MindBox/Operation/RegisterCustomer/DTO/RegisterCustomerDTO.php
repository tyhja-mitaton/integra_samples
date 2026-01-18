<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\RegisterCustomer\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

final class RegisterCustomerDTO extends AbstractDTO
{
    public function __construct(
        public readonly string      $executionDateTimeUtc,
        public readonly CustomerDTO $customer,
    )
    {
    }

    /**
     * @return string[]
     */
    protected function fields(): array
    {
        return [
            'executionDateTimeUtc',
            'customer',
        ];
    }
}
