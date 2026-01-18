<?php

namespace Integra\Domain\Integration\Affise\Operation\Bet\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class BetDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $affiseDeviceId,
        public readonly float    $price,
        public readonly string    $quantity,
        public readonly float    $revenue,
        public readonly string $receiptId,
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
            'receiptId',
            'currency'
        ];
    }
}