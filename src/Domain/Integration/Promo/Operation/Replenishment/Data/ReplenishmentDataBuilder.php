<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Promo\Operation\Replenishment\Data;

use Integra\Domain\Integration\Promo\Operation\Replenishment\DTO\ReplenishmentPromoDTO;
use Integra\Models\Ubet\Payment;
use Integra\Domain\Integration\Promo\Enum\TypeIdEnum;
use yii\helpers\StringHelper;
use DomainException;
use Yii;

final class ReplenishmentDataBuilder
{
    public function build(int $payId): ReplenishmentPromoDTO
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

        return new ReplenishmentPromoDTO(
            userId: $payment->user_id,
            paymentId: $payment->pay_id,
            typeId: (int)TypeIdEnum::POPOLNENIE->value()
        );
    }
}