<?php

namespace Integra\Domain\Integration\Adjust\Operation\FirstTimeDeposit\Data;

use Integra\Domain\Integration\Adjust\Operation\FirstTimeDeposit\DTO\CallbackParamsDTO;
use Integra\Domain\Integration\Adjust\Operation\FirstTimeDeposit\DTO\FirstTimeDepositAdjustDTO;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Ubet\Payment;
use DateTime;

final class RecurringDepositAdjustDataBuilder
{
    private const STATUS_CONFIRMED = 'confirmed';

    public function build(int $userId, int $paymentId, string  $partnerId = '', ?string $offerId = '',): FirstTimeDepositAdjustDTO
    {
        $payment = Payment::findOne($paymentId);

        $paymentDateTime = new DateTime($payment->dttm_end, new Asia());
        $paymentDateTime->setTimezone(new UTC());

        $callbackDto = new CallbackParamsDTO(
            userId: (string)$userId,
            payId: (string)$paymentId,
            status: self::STATUS_CONFIRMED,
            goal: AlanbaseGoalEnum::RECURRING_DEPOSIT->value,
            amount: $payment->amount,
            currency: $payment->currency,
            datetime: (string)$paymentDateTime->getTimestamp(),
            partnerId: $partnerId ?? null,
            offerId: $offerId,
        );

        return new FirstTimeDepositAdjustDTO(
            callbackParams: $callbackDto
        );
    }
}