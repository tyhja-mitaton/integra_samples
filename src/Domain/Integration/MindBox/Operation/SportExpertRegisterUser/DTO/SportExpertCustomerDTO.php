<?php

declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\SportExpertRegisterUser\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;
use Integra\Domain\Integration\Common\DTOInterface;

final class SportExpertCustomerDTO extends AbstractDTO
{
    public function __construct(
        public readonly string                     $mobilePhone,
        public readonly DTOInterface               $ids,
        public readonly SportExpertCustomFieldsDTO $customFields,
        public readonly array                      $subscriptions,
    )
    {
    }

    protected function fields(): array
    {
        return ['mobilePhone', 'ids', 'customFields', 'subscriptions'];
    }
}