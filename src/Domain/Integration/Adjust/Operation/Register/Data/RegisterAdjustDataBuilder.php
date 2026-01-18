<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Adjust\Operation\Register\Data;

use DateTime;
use DomainException;
use Integra\Models\Ubet\User;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Domain\Integration\Adjust\DTO\DeviceIdentifiersDTO;
use Integra\Domain\Integration\Adjust\Operation\Register\DTO\CallbackParamsDTO;
use Integra\Domain\Integration\Adjust\Operation\Register\DTO\RegisterAdjustDTO;

final class RegisterAdjustDataBuilder
{
    private const STATUS_CONFIRMED = 'confirmed';

    public function build(
        int     $userId,
        ?string $adjustAdid,
        ?string $gpsAdid,
        ?string $idfa,
        ?string $idfv,
        string  $partnerId = '',
        ?string $offerId = '',
    ): RegisterAdjustDTO
    {
        $user = User::findOne(['user_id' => $userId]) ?? throw new DomainException("User not found: {$userId}");

        $registrationDateTime = new DateTime($user->dttm_reg, new Asia());
        //todo тут везде -6 на самом деле стояло, но это выглядит как ошибка, поправил, потом при тесте узнаем
        $registrationDateTime->setTimezone(new UTC());
        $timestamp = $registrationDateTime->getTimestamp();

        $callbackDto = new CallbackParamsDTO(
            userId: (string)$userId,
            status: self::STATUS_CONFIRMED,
            goal: AlanbaseGoalEnum::REGISTRATION->value,
            datetime: (string)$timestamp,
            partnerId: $partnerId ?? null,
            offerId: $offerId,
        );

        $identifiersDto = new DeviceIdentifiersDTO(
            adjustAdid: $adjustAdid,
            gpsAdid: $gpsAdid,
            idfa: $idfa,
            idfv: $idfv,
        );

        return new RegisterAdjustDTO(
            callbackParams: $callbackDto,
            identifiers: $identifiersDto
        );
    }
}