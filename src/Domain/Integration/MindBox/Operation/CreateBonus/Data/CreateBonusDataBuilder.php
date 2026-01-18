<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateBonus\Data;

use Integra\Domain\Integration\MindBox\Enum\BonusBetTypeEnum;
use Integra\Domain\Integration\MindBox\Enum\BonusTypeEnum;
use Integra\Domain\Integration\MindBox\Operation\CreateBonus\DTO\CreateBonusDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBonus\DTO\CustomerDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBonus\DTO\CustomFieldsDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBonus\DTO\IdsDTO as IdsDTOOrder;
use Integra\Domain\Integration\MindBox\DTO\IdsDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBonus\DTO\IdsDTOProduct;
use Integra\Domain\Integration\MindBox\Operation\CreateBonus\DTO\LineDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBonus\DTO\OrderDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBonus\DTO\ProductDTO;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Ubet\Promocode;
use Integra\Models\Ubet\TotoBonus;
use Integra\Models\Ubet\UserTotoBonus;
use DateTime;

final class CreateBonusDataBuilder
{
    const BONUS_NAME = 'Промокод';
    const BASE_PRICE_PER_ITEM_0 = 0;
    const QUANTITY = '1';
    const UBET = 'bonus';
    const ORDER_TYPE = 'Bonuses';

    public function build(int $bonusId): CreateBonusDTO
    {
        $userTotoBonus = UserTotoBonus::findOne(['bonus_id' => $bonusId]);
        if(isset($userTotoBonus->toto_bonus_id)) {
            $bonusData = TotoBonus::findOne($userTotoBonus->toto_bonus_id);
            $hoursForActivation = $bonusData->period_wait;
            $hoursForWinback = $bonusData->period_activ;
            $bonusName = $bonusData->name;
            $wageringCoefficient = $bonusData->wageringTurnover;
        }

        if(isset($userTotoBonus->promocode_id)) {
            $promocode = Promocode::findOne($userTotoBonus->promocode_id);
            $hoursForActivation = $promocode->time_to_activation;
            $hoursForWinback = $promocode->active_tm;
            $bonusName = self::BONUS_NAME;
            $wageringCoefficient = $promocode->wagering_amount/$userTotoBonus->amount;
        }

        $bonusCreateDateTime = isset($userTotoBonus->create_dttm) ? (new DateTime($userTotoBonus->create_dttm, new Asia())) : null;
        if (!empty($bonusCreateDateTime)){
            $bonusCreateDateTime->setTimezone(new UTC());
            $bonusCreateDateTimeUTC = $bonusCreateDateTime->format('Y-m-d H:i:s');
        } else {
            $bonusCreateDateTimeUTC = null;
        }

        $bonusEndDateTime = isset($userTotoBonus->end_dttm) ? (new DateTime($userTotoBonus->end_dttm, new Asia())) : null;
        if (!empty($bonusEndDateTime)){
            $bonusEndDateTime->setTimezone(new UTC());
            $bonusEndDateTimeUTC = $bonusEndDateTime->format('Y-m-d H:i:s');
        } else {
            $bonusEndDateTimeUTC = null;
        }

        $winbackDateAndTime = null;
        $bonusActivationDateAndTime = null;
        $bonusActiveDateTime = $userTotoBonus->activ_dttm != null ? (new DateTime($userTotoBonus->activ_dttm, new Asia())) : null;
        if (!empty($bonusActiveDateTime)) {
            $bonusActiveDateTime->setTimezone(new UTC());
            $winbackDateAndTime = $bonusActiveDateTime->modify('+' . $hoursForWinback . ' hours')->format('Y-m-d H:i:s');
            $bonusActivationDateAndTime = $bonusActiveDateTime->format('Y-m-d H:i:s');
        }

        $winbackAmount = null;
        if($userTotoBonus->bonus_type == 1) {
            $wageringCoefficient = (int)$wageringCoefficient;
            $winbackAmount = $userTotoBonus->wageringTurnover;
        }

        $lines = [];
        $line = new LineDTO(
            basePricePerItem: self::BASE_PRICE_PER_ITEM_0,
            quantity: self::QUANTITY,
            product: new ProductDTO(
                ids: new IdsDTOProduct(
                    ubet: self::UBET
                )
            ),
        );
        $lines[] = $line;

        return new CreateBonusDTO(
            executionDateTimeUtc: $bonusCreateDateTimeUTC,
            customer: new CustomerDTO(
                ids: new IdsDTO(
                    login: $userTotoBonus->user_id
                )
            ),
            order: new OrderDTO(
                ids: new IdsDTOOrder(
                    bonuseId: $userTotoBonus->bonus_id
                ),
                customFields: new CustomFieldsDTO(
                    bonusAccrualDateAndTime: $bonusCreateDateTimeUTC,
                    bonuseAmount: $userTotoBonus->amount,
                    dateAndTimeOfBonusExpiration: $bonusEndDateTimeUTC,
                    orderType: self::ORDER_TYPE,
                    bonusId: $userTotoBonus->toto_bonus_id,
                    bonusType: BonusTypeEnum::from($userTotoBonus->bonus_type)->typeName(),
                    winbackDateAndTime: $winbackDateAndTime,
                    bonusActivationDateAndTime: $bonusActivationDateAndTime,
                    minimumoddsforbet: $userTotoBonus->min_betFactor,
                    typeOfBonusBet: BonusBetTypeEnum::from($userTotoBonus->allowedBetTypeID)->typeName(),
                    daysForActivation: (int)($hoursForActivation/24),
                    daysForWinback: (int)($hoursForWinback/24),
                    bonusName: $bonusName,
                    wageringCoefficient: $wageringCoefficient,
                    winbackAmount: $winbackAmount
                ),
                lines: $lines
            )
        );
    }
}