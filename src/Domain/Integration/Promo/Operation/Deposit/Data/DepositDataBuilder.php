<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Promo\Operation\Deposit\Data;

use Integra\Domain\Integration\Promo\Operation\Deposit\DTO\DepositPromoDTO;
use Integra\Models\Ubet\Payment;
use yii\helpers\StringHelper;
use DomainException;
use Yii;
use Integra\Domain\Integration\Promo\Enum\TypeIdEnum;

final class DepositDataBuilder
{
    /**
     * @param int $payId
     * @return DepositPromoDTO
     */
    public function build(int $payId): DepositPromoDTO
    {
        $payment = Payment::findOne($payId);

        if (empty($payment)) {
            $message = sprintf(
                '[%s] Payment not found:: %s',
                StringHelper::basename(self::class),
                $payId
            );
            Yii::error($message, __METHOD__);
            throw new DomainException($message);
        }

        return new DepositPromoDTO(
            userId: $payment->user_id,
            paymentId: $payment->pay_id,
            typeId: (int)TypeIdEnum::DEPOSIT->value()
        );
    }
}