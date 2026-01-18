<?php

declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\SportExpertRegisterUser\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

final class SportExpertRegisterCustomerDTO extends AbstractDTO
{
    public function __construct(
        public readonly string                 $executionDateTimeUtc,
        public readonly SportExpertCustomerDTO $customer,
    )
    {
    }

    protected function fields(): array
    {
        return ['executionDateTimeUtc', 'customer'];
    }
}