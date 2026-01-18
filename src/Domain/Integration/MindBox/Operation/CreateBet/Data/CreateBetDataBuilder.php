<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateBet\Data;

use Integra\Domain\Integration\MindBox\DTO\IdsDTO;
use Integra\Domain\Integration\MindBox\Enum\BetTypeEnum;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\CreateBetDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\CustomerDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\CustomFieldsDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\IdsDTOProduct;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\OrderDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\LineDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\LineCustomFieldsDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\LineProductDTO;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Feed\FeedMatch;
use Integra\Models\Ubet\Promocode;
use Integra\Models\Ubet\SportBet;
use Integra\Models\Ubet\SportBetOrder;
use Integra\Models\Ubet\TotoBonus;
use Integra\Models\Ubet\UserTotoBonus;
use yii\helpers\StringHelper;
use Yii;
use DomainException;
use DateTime;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\IdsDTOOrder;

final class CreateBetDataBuilder
{
    const ORDER_TYPE = 'bet';
    const UBET = 'Bet';
    const QUANTITY = '1';
    const BASE_PRICE_PER_ITEM_0 = '0';
    const BASE_PRICE_PER_ITEM_1 = '1';
    const LIVE = 'Live';
    const LINE = 'Line';

    public function build(int $orderNumber): CreateBetDTO
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

        $sportBetOrders = SportBetOrder::find()->where(['bet_id' => $bet->bet_id])->all();

        if (empty($sportBetOrders)) {
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

        $isBetWithRealMoney = !(is_numeric($bet->bonus) && $bet->bonus > 0);

        $betOnBonus = null;
        if ($isBetWithRealMoney) {
            $betOnBonus = 0;
        } else {
            if ($bet->bonus_id != null) {
                $userTotoBonus = UserTotoBonus::findOne(['bonus_id' => $bet->bonus_id]);
                if ($userTotoBonus) {
                    if ($userTotoBonus->toto_bonus_id != null) {
                        $totoBonus = TotoBonus::findOne($userTotoBonus->toto_bonus_id);
                        if ($totoBonus) {
                            $betOnBonus = $totoBonus->id;
                        }
                    }
                    if ($userTotoBonus->promocode_id != null) {
                        $promocode = Promocode::findOne($userTotoBonus->promocode_id);
                        if ($promocode) {
                            $betOnBonus = $promocode->public_code;
                        }
                    }
                }
            }
        }

        $ordersCount = count($sportBetOrders);
        $lineCount = max($ordersCount, 1);
        $lines = [];

        if ($ordersCount > 0) {
            foreach ($sportBetOrders as $sportBetsOrder) {
                $match = FeedMatch::findOne(['id' => $sportBetsOrder->eventId]);
                if ($match && $match->event_date_ticks_at) {
                    $eventDateTicksAt = new DateTime($match->event_date_ticks_at, new Asia());
                    $eventDateTicksAt = $eventDateTicksAt->format('Y-m-d H:i:s');
                } else {
                    $eventDateTicksAt = null;
                }
                $line = new LineDTO(
                    customFields: new LineCustomFieldsDTO(
                        result: $sportBetsOrder->fullStake,
                        matchdateandtime: $eventDateTicksAt,
                        championship: $sportBetsOrder->tournamentName,
                        lineLive: $sportBetsOrder->isLive ? self::LIVE : self::LINE,
                        sport: $sportBetsOrder->sportID,
                        game: $sportBetsOrder->eventNameOnly
                    ),
                    quantity: self::QUANTITY,
                    product: new LineProductDTO(
                        ids: new IdsDTOProduct(
                            ubet: self::UBET
                        )
                    )
                );

                $lines[] = $line;
            }
        } else {
            $line = new LineDTO(
                customFields: new LineCustomFieldsDTO(),
                quantity: self::QUANTITY,
                product: new LineProductDTO(
                    ids: new IdsDTOProduct(
                        ubet: self::UBET
                    )
                )
            );
            $lines[] = $line;
        }

        $basePrice = $isBetWithRealMoney ? $bet->bet_sum : 0.00;

        if ($basePrice > 0) {
            if ($lineCount === 1) {
                $lines[0]->basePricePerItem = (string)$basePrice;
            } else {
                for ($i = 0; $i < $lineCount; $i++) {
                    if ($i < $lineCount - 1) {
                        $lines[$i]->basePricePerItem = self::BASE_PRICE_PER_ITEM_1;
                    } else {
                        $lines[$i]->basePricePerItem = (string)($basePrice - ($lineCount - 1));
                    }
                }
            }
        } else {
            foreach ($lines as $line) {
                $line->basePricePerItem = self::BASE_PRICE_PER_ITEM_0;
            }
        }

        return new CreateBetDTO(
            executionDateTimeUtc: $betDateTimeUTC,
            customer: new CustomerDTO(
                ids: new IdsDTO(
                    login: $bet->user_id
                )
            ),
            order: new OrderDTO(
                ids: new IdsDTOOrder(
                    betId: $bet->order_number
                ),
                customFields: new CustomFieldsDTO(
                    betAmount: $bet->bet_sum,
                    betOnBonus: $betOnBonus,
                    betType: BetTypeEnum::tryFrom($bet->type_id)?->value,
                    eventDateAndTime: $betDateTimeUTC,
                    eventPlatform: null,
                    finalBetOdds: strval(round($bet->factor ?? 0, 2)),
                    orderType: self::ORDER_TYPE,
                    prizeAmount: null
                ),
                lines: $lines
            )
        );
    }
}