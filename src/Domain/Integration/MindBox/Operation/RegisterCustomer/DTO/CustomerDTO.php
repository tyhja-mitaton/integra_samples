<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\RegisterCustomer\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;
use Integra\Domain\Integration\MindBox\DTO\IdsDTO;
use Integra\Domain\Integration\MindBox\DTO\SubscriptionDTO;

final class CustomerDTO extends AbstractDTO
{
    /**
     * @param SubscriptionDTO[] $subscriptions
     */
    public function __construct(
        public readonly string          $mobilePhone,
        public readonly IdsDTO          $ids,
        public readonly CustomFieldsDTO $customFields,
        public readonly array           $subscriptions,
    )
    {
    }

    /**
     * @return string[]
     */
    protected function fields(): array
    {
        return [
            'mobilePhone',
            'ids',
            'customFields',
            'subscriptions',
        ];
    }
}
