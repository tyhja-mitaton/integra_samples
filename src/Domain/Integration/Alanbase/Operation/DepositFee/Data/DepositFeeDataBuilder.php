<?php

namespace Integra\Domain\Integration\Alanbase\Operation\DepositFee\Data;

use Integra\Domain\Integration\Alanbase\Operation\DepositFee\DTO\DepositFeeDTO;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Ubet\Payment;
use DateTime;
use DomainException;

final class DepositFeeDataBuilder
{
    const STATUS = 'confirmed';

    public function build($payId, $clickId):DepositFeeDTO
    {
        $payment = Payment::findOne($payId);

        if (!$payment) {
            throw new DomainException("Payment not found: {$payId}");
        }

        $paymentDateTime = new DateTime($payment->dttm_end, new Asia());
        $paymentDateTime->setTimezone(new UTC());

        return new DepositFeeDTO(
            clickId: $clickId,
            goal: AlanbaseGoalEnum::DEPOSIT_FEE->value,
            value: $payment->fee,
            currency: $payment->currency,
            status: self::STATUS,
            tid: $payment->pay_id,
            userId: $payment->user_id,
            datetime: $paymentDateTime->getTimestamp(),
        );
    }
}