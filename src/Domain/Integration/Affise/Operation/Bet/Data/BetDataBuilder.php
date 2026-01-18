<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Affise\Operation\Bet\Data;

use Integra\Domain\Integration\Affise\Operation\Bet\DTO\BetDTO;

final class BetDataBuilder
{
    const QUANTITY = '1';
    const CURRENCY = 'KZ';

    /**
     * @param string $affiseDeviceId
     * @param float $betSum
     * @param string $receiptId
     * @return BetDTO
     */
    public function build(string $affiseDeviceId, float $betSum, string $receiptId): BetDTO
    {
        return new BetDTO(
            affiseDeviceId: $affiseDeviceId,
            price: $betSum,
            quantity: self::QUANTITY,
            revenue: $betSum,
            receiptId: $receiptId,
            currency: self::CURRENCY
        );
    }
}