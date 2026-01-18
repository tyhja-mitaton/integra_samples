<?php

namespace Integra\Models\Ubet;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use Yii;

/**
 * @property int $bet_id
 * @property int $order_number
 * @property int $user_id
 * @property string $game_id
 * @property string $tr_name
 * @property string $bet_dttm
 * @property string $end_dttm
 * @property float $bet_sum
 * @property float $bet_win
 * @property float $bet_win_real
 * @property bool $bonus
 * @property int $bonus_id
 * @property int $type_id
 * @property float $factor
 * @property int $bet_state
 * @property string $currency_id
 * @property int $transaction_id
 */
class SportBet extends ActiveRecord
{
    /**
     * @throws InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('db_ubet');
    }

    public static function tableName(): string
    {
        return '{{%sport_bets}}';
    }
}