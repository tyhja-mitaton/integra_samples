<?php

namespace Integra\Domain\Integration\AppsFlyer\Operation\Deposit\Data;

use Integra\Domain\Enum\PlatformNameEnum;
use Integra\Domain\Integration\AppsFlyer\Enum\S2SAppIdEnum;
use Integra\Domain\Integration\AppsFlyer\Operation\Deposit\DTO\DepositAppsFlyerDTO;
use Integra\Domain\Integration\AppsFlyer\Operation\Deposit\DTO\EventValueDTO;
use Integra\Models\Ubet\Payment;
use DomainException;

final class DepositAppsFlyerDataBuilder
{
    public function build(int $paymentId, string $device)
    {
        $payment = Payment::findOne($paymentId);

        $appId = match ($device) {
            PlatformNameEnum::ANDROID->value() => S2SAppIdEnum::DEPOSIT_ANDROID,
            PlatformNameEnum::IOS->value() => S2SAppIdEnum::DEPOSIT_IOS,
            default => throw new DomainException('Unsupported AppsFlyer platform: ' . $device),
        };

        return new DepositAppsFlyerDTO(
            appsflyerId: $appId->value(),
            eventName: 'af_purchase',
            eventValue: new EventValueDTO(
                afRevenue: $payment->amount,
                afCurrency: $payment->currency,
                afCustomerUserId: $payment->user_id,
            )
        );
    }
}