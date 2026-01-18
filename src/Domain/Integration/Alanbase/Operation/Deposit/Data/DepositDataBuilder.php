<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Alanbase\Operation\Deposit\Data;

use Integra\Domain\Integration\Alanbase\Operation\Deposit\DTO\DepositDTO;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Ubet\Payment;
use DomainException;
use DateTime;

final class DepositDataBuilder
{
    public function build($payId, $clickId): DepositDTO
    {
        $payment = Payment::findOne($payId);

        if (!$payment) {
            throw new DomainException("Payment not found: {$payId}");
        }

        $paymentDateTime = new DateTime($payment->dttm_end, new Asia());
        $paymentDateTime->setTimezone(new UTC());

        return new DepositDTO(
            clickId: $clickId,
            event: AlanbaseGoalEnum::DEPOSIT->value,
            value: $payment->amount,
            currency: $payment->currency,
            userId: $payment->user_id,
            payId: $payment->pay_id,
            datetime: $paymentDateTime->getTimestamp(),
        );
    }
}