<?php

namespace Integra\Domain\Services\User;

use Integra\Infrastructure\Datetime\Asia;
use yii\db\Connection;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use DateTime;
use yii\db\Exception;

class BetInfoService
{
    private Connection $db;

    public function __construct()
    {
        $this->db = Yii::$app->db_ubet;
    }

    /**
     * общая сумма ставок (ставки на спорт + быстрые игры)
     * @param int $userId
     * @return float
     * @throws \DateMalformedStringException
     * @throws Exception
     */
    public function getCommonBetSum(int $userId): float
    {
        $betDateTime = (new DateTime('now',new Asia()))
            ->modify('-1 month');
        $betDateTimeUTC = (new DateTime('now'))->modify('-1 month');
        $dateFrom = $betDateTime->format('Y-m-01 00:00:00');
        $dateTo = $betDateTime->format('Y-m-t 23:59:59');
        $dateFromUTC = $betDateTimeUTC->format('Y-m-01 00:00:00');
        $dateToUTC = $betDateTimeUTC->format('Y-m-t 23:59:59');

        $gameBetQuery = (new Query())
            ->select([
                'user_id',
                'total_amount' => new Expression('ABS(SUM(IF(amount < 0, amount, 0)))'),
            ])
            ->from('game_bets')
            ->where(['between', 'dttm_at', $dateFromUTC, $dateToUTC])
            ->groupBy('user_id');

        $sportBetQuery = (new Query())
            ->select([
                'user_id',
                'total_amount' => new Expression('SUM(bet_sum)'),
            ])
            ->from('sport_bets')
            ->where([
                'tr_name' => 'CreditBet',
                'bonus' => 0,
                'user_id' => $userId,
            ])
            ->andWhere(['between', 'end_dttm', $dateFrom, $dateTo]);

        $unionQuery = (new Query())
            ->select(['user_id', 'total_amount'])
            ->from(['combined' => $gameBetQuery->union($sportBetQuery, true)])
            ->where(['user_id' => $userId]);

        $finalQuery = (new Query())
            ->select(['total_bets' => new Expression('SUM(total_amount)')])
            ->from(['combined' => $unionQuery]);

        return (float)$finalQuery->createCommand($this->db)->queryScalar();
    }

    /**
     * процент ставок на определенный вид спорта
     * @param int $sportId
     * @param int $userId
     * @return float
     * @throws \DateMalformedStringException
     * @throws Exception
     */
    public function getBetPercent(int $sportId, int $userId): float
    {
        $betDateTime = (new DateTime('now',new Asia()))
            ->modify('-1 month');
        $dateFrom = $betDateTime->format('Y-m-01 00:00:00');
        $dateTo = $betDateTime->format('Y-m-t 23:59:59');

        $sportTotalsSubquery = (new Query())
            ->select([
                'sport_count' => new Expression('COUNT(*)'),
                'sb.user_id',
            ])
            ->from(['sb' => 'sport_bets'])
            ->where(['between', 'sb.bet_dttm', $dateFrom, $dateTo])
            ->andWhere(['sb.user_id' => $userId])
            ->andWhere(['in', 'sb.bet_id',
                (new Query())
                    ->select(['sbo.bet_id'])
                    ->distinct()
                    ->from(['sbo' => 'sport_bets_order'])
                    ->where(['sbo.sportID' => $sportId])
            ]);

        $query = (new Query())
            ->select([
                'bet_percent' => new Expression('IFNULL(ROUND(sport_totals.sport_count / COUNT(*) * 100, 2), 0)')
            ])
            ->from('sport_bets')
            ->leftJoin(['sport_totals' => $sportTotalsSubquery], 'sport_bets.user_id = sport_totals.user_id')
            ->where(['between', 'sport_bets.bet_dttm', $dateFrom, $dateTo])
            ->andWhere(['sport_bets.user_id' => $userId]);

        return (float)$query->createCommand($this->db)->queryScalar();
    }

    /**
     * процент ставок на e-sports
     * @param int $userId
     * @return float
     * @throws \DateMalformedStringException
     * @throws Exception
     */
    public function getESportPercent(int $userId): float
    {
        $betDateTime = (new DateTime('now', new Asia()))
            ->modify('-1 month');
        $dateFrom = $betDateTime->format('Y-m-01 00:00:00');
        $dateTo = $betDateTime->format('Y-m-t 23:59:59');

        $sportIdSubquery = (new Query())
            ->select('sportID')
            ->from('sports')
            ->where(['like', 'name', 'E-%', false]);

        $sportTotalsSubquery = (new Query())
            ->select([
                'sport_count' => new Expression('COUNT(*)'),
                'sb.user_id',
            ])
            ->from(['sb' => 'sport_bets'])
            ->where(['between', 'sb.bet_dttm', $dateFrom, $dateTo])
            ->andWhere(['sb.user_id' => $userId])
            ->andWhere(['in', 'sb.bet_id',
                (new Query())
                    ->select('sbo.bet_id')
                    ->distinct()
                    ->from(['sbo' => 'sport_bets_order'])
                    ->where(['in', 'sbo.sportID', $sportIdSubquery])
            ]);

        $query = (new Query())
            ->select([
                'bet_percent' => new Expression('IFNULL(ROUND(sport_totals.sport_count / COUNT(*) * 100, 2), 0)'),
            ])
            ->from('sport_bets')
            ->leftJoin(['sport_totals' => $sportTotalsSubquery], 'sport_bets.user_id = sport_totals.user_id')
            ->where(['between', 'sport_bets.bet_dttm', $dateFrom, $dateTo])
            ->andWhere(['sport_bets.user_id' => $userId]);

        return (float)$query->createCommand($this->db)->queryScalar();
    }

}