<?php

namespace Integra\Domain\Integration\AppsFlyer\Operation\Deposit\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class EventValueDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $afRevenue,
        public readonly string $afCurrency,
        public readonly string $afCustomerUserId,
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'afRevenue' => 'af_revenue',
            'afCurrency' => 'af_currency',
            'afCustomerUserId' => 'af_customer_user_id',
        ];
    }
}