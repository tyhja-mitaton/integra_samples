<?php

namespace Integra\Domain\Integration\Affise\Operation\Deposit\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class DepositDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $affiseDeviceId,
        public readonly float    $price,
        public readonly string    $quantity,
        public readonly float    $revenue,
        public readonly string $currency
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'affiseDeviceId',
            'price',
            'quantity',
            'revenue',
            'currency'
        ];
    }
}