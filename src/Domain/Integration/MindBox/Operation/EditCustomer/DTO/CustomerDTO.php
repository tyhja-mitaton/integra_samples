<?php

namespace Integra\Domain\Integration\MindBox\Operation\EditCustomer\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;
use Integra\Domain\Integration\MindBox\DTO\IdsDTO;
use Integra\Domain\Integration\MindBox\DTO\SubscriptionDTO;

class CustomerDTO extends AbstractDTO
{
    /**
     * @param SubscriptionDTO[] $subscriptions
     */
    public function __construct(
        public readonly string          $mobilePhone,
        public readonly ?string         $email,
        public readonly string          $timeZone,
        public readonly IdsDTO          $ids,
        public readonly CustomFieldsDTO $customFields,
        public readonly array           $subscriptions,
        public ?string                  $birthDate = null,
        public ?string                  $sex = null,
        public ?string                  $lastName = null,
        public ?string                  $firstName = null,
        public ?string                  $middleName = null,
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'mobilePhone',
            'email',
            'timeZone',
            'ids',
            'customFields',
            'subscriptions',
            'birthDate',
            'sex',
            'lastName',
            'firstName',
            'middleName',
        ];
    }
}