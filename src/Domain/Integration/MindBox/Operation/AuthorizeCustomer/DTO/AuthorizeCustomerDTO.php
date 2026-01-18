<?php

declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\AuthorizeCustomer\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

final class AuthorizeCustomerDTO extends AbstractDTO
{
    public function __construct(
        public readonly CustomerDTO $customer,
        public readonly string      $executionDateTimeUtc,
        public readonly ?string     $deviceUUID,
        public readonly int         $customerIp,
        public readonly ?string     $userAgent,
    )
    {
    }

    protected function fields(): array
    {
        return [
            'customer',
            'executionDateTimeUtc',
            'deviceUUID',
            'customerIp',
            'userAgent',
        ];
    }

    /**
     * @return int
     */
    public function getCustomerIp(): int
    {
        return $this->customerIp;
    }

    /**
     * @return string|null
     */
    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }
}