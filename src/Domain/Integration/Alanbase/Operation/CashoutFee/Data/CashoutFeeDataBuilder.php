<?php

namespace Integra\Domain\Integration\Alanbase\Operation\CashoutFee\Data;

use Integra\Infrastructure\Datetime\Asia;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Ubet\Payment;
use DateTime;

final class CashoutFeeDataBuilder
{
    const STATUS = 'confirmed';

    public function build($payId, $clickId)
    {
        $payment = Payment::findOne($payId);

        $betBonusDateTime = new DateTime($payment->dttm_end, new Asia());
        $betBonusDateTime->setTimezone(new UTC());
    }
}