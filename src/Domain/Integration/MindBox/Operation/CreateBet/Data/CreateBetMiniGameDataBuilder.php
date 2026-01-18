<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateBet\Data;

use Integra\Domain\Integration\MindBox\DTO\IdsDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\CreateBetDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\CustomerDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\CustomFieldsDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\IdsDTOOrder;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\IdsDTOProduct;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\LineCustomFieldsDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\LineDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\LineProductDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO\OrderDTO;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Ubet\Game;
use Integra\Models\Ubet\GameBet;
use Integra\Models\Ubet\GameProvider;
use DateTime;

final class CreateBetMiniGameDataBuilder
{
    const ORDER_TYPE = 'bet';
    const UBET = 'Bet';
    const QUANTITY = '1';
    const LIVE = 'Live';
    const BET_ON_BONUS = 0;
    const BET_TYPE = 'Ordinar';
    const SPORT = 'Быстрые игры';

    public function build(int $gameId): CreateBetDTO
    {
        $bet = GameBet::findOne(['bet_id' => $gameId]);
        $game = Game::findOne($bet->game_id);
        $championshipName = GameProvider::findOne($game->provider_id)->name;
        $gameName = $game->title_ru;

        $betDateTime = new DateTime($bet->dttm_at,new UTC());

        $lines = [];
        $line = new LineDTO(
            customFields: new LineCustomFieldsDTO(
                championship: $championshipName,
                lineLive: self::LIVE,
                sport: self::SPORT,
                game: $gameName
            ),
            quantity: self::QUANTITY,
            product: new LineProductDTO(
                ids: new IdsDTOProduct(
                    ubet: self::UBET
                )
            )
        );
        $lines[] = $line;

        return new CreateBetDTO(
            executionDateTimeUtc: $betDateTime->format('Y-m-d H:i:s'),
            customer: new CustomerDTO(
                ids: new IdsDTO(
                    login: $bet->user_id
                )
            ),
            order: new OrderDTO(
                ids: new IdsDTOOrder(
                    betId: $bet->provider_bet_id
                ),
                customFields: new CustomFieldsDTO(
                    betAmount: abs((int)$bet->amount),
                    betOnBonus: self::BET_ON_BONUS,
                    betType: self::BET_TYPE,
                    eventDateAndTime: $betDateTime->format('Y-m-d H:i:s'),
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