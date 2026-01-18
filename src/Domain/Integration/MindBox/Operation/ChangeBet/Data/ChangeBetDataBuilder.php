<?php

namespace Integra\Domain\Integration\MindBox\Operation\ChangeBet\Data;

use Integra\Domain\Integration\MindBox\Enum\BetTypeEnum;
use Integra\Domain\Integration\MindBox\Operation\ChangeBet\DTO\ChangeBetDTO;
use Integra\Domain\Integration\MindBox\Operation\ChangeBet\DTO\CustomFieldsDTO;
use Integra\Domain\Integration\MindBox\Operation\ChangeBet\DTO\IdsDTOOrder;
use Integra\Domain\Integration\MindBox\Operation\ChangeBet\DTO\IdsDTOProduct;
use Integra\Domain\Integration\MindBox\Operation\ChangeBet\DTO\LineCustomFieldsDTO;
use Integra\Domain\Integration\MindBox\Operation\ChangeBet\DTO\LineDTO;
use Integra\Domain\Integration\MindBox\Operation\ChangeBet\DTO\LineProductDTO;
use Integra\Domain\Integration\MindBox\Operation\ChangeBet\DTO\OrderDTO;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Ubet\Promocode;
use Integra\Models\Ubet\SportBet;
use Integra\Models\Ubet\SportBetOrder;
use Integra\Models\Ubet\TotoBonus;
use Integra\Models\Ubet\UserTotoBonus;
use yii\helpers\StringHelper;
use Yii;
use DomainException;
use DateTime;

final class ChangeBetDataBuilder
{
    const ORDER_TYPE = 'bet';
    const UBET = 'Bet';
    const QUANTITY = '1';
    const BASE_PRICE_PER_ITEM_0 = '0';
    const DISCOUNTED_PRICE_LINE = '0';
    const LIVE = 'Live';
    const LINE = 'Line';

    public function build(int $orderNumber): ChangeBetDTO
    {
        $bet = SportBet::findOne(['order_number' => $orderNumber, 'tr_name' => 'CreditBet']);

        if (!$bet) {
            $message = sprintf(
                '[%s] Bet not found:: %s',
                StringHelper::basename(self::class),
                $orderNumber
            );
            Yii::error($message, __METHOD__);
            throw new DomainException($message);
        }

        $sportBetsOrders = SportBetOrder::find()->where(['bet_id' => $bet->bet_id])->all();
        //print_r($sportBetsOrders);die;
        //echo empty($sportBetsOrders);die;

        if (empty($sportBetsOrders)) {
            $message = sprintf(
                '[%s] SportBetsOrder not found:: %s',
                StringHelper::basename(self::class),
                $orderNumber
            );
            Yii::error($message, __METHOD__);
            throw new DomainException($message);
        }

        $betDateTime = new DateTime($bet->bet_dttm, new Asia());
        $betDateTime->setTimezone(new UTC());
        $betDateTimeUTC = $betDateTime->format('Y-m-d H:i:s');

        $betOnBonus = null;
        if ($bet->bonus == 0) {
            $betOnBonus = 0;
        } else {
            if(isset($bet->bonus_id)){
                $usersTotoBonus = UserTotoBonus::findOne(['bonus_id' => $bet->bonus_id]);
                if($usersTotoBonus){
                    if(isset($usersTotoBonus->toto_bonus_id)){
                        $totoBonus = TotoBonus::findOne(['id' => $usersTotoBonus->toto_bonus_id]);
                        if ($totoBonus) {
                            $betOnBonus = $totoBonus->name;
                        }
                    }
                    if(isset($usersTotoBonus->promocode_id)){
                        $promocode = Promocode::findOne(['promocode_id' => $usersTotoBonus->promocode_id]);
                        if ($promocode) {
                            $betOnBonus = 'Промокод';
                        }
                    }
                }
            }
        }

        $lines = [];

        foreach ($sportBetsOrders as $sportBetsOrder)
        {
            $line = new LineDTO(
                customFields: new LineCustomFieldsDTO(
                    championship: $sportBetsOrder->tournamentName,
                    lineLive: $sportBetsOrder->isLive ? self::LIVE : self::LINE,
                    sport: $sportBetsOrder->sportName,
                    game: $sportBetsOrder->eventNameOnly,
                ),
                quantity: self::QUANTITY,
                product: new LineProductDTO(
                    ids: new IdsDTOProduct(
                        ubet: self::UBET
                    )
                ),
                basePricePerItem: self::BASE_PRICE_PER_ITEM_0,
                status: $sportBetsOrder->stakeStatus,
                discountedPriceOfLine: self::DISCOUNTED_PRICE_LINE
            );
            $lines[] = $line;
        }

            return new ChangeBetDTO(
            executionDateTimeUtc: $betDateTimeUTC,
            order: new OrderDTO(
                ids: new IdsDTOOrder(
                    betId: $bet->order_number,
                ),
                customFields: new CustomFieldsDTO(
                    betAmount: $bet->bet_sum,
                    betOnBonus: $betOnBonus,
                    betType: BetTypeEnum::tryFrom($bet->type_id)?->value,
                    eventDateAndTime: $betDateTimeUTC,
                    finalBetOdds: strval(round($bet->factor ?? 0, 2)),
                    orderType: self::ORDER_TYPE
                ),
                lines: $lines
            )
        );
    }
}