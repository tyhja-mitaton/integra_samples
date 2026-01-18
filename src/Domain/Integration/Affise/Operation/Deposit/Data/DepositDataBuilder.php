<?php

namespace Integra\Domain\Integration\Affise\Operation\Deposit\Data;

use Integra\Domain\Integration\Affise\Operation\Deposit\DTO\DepositDTO;
use Integra\Models\Ubet\Payment;

final class DepositDataBuilder
{
    const QUANTITY = '1';
    const CURRENCY = 'KZ';

    public function build(string $affiseDeviceId, int $paymentId): DepositDTO
    {
        $payment = Payment::findOne($paymentId);

        return new DepositDTO(
            affiseDeviceId: $affiseDeviceId,
            price: $payment->amount,
            quantity: self::QUANTITY,
            revenue: $payment->amount,
            currency: self::CURRENCY
        );
    }
}