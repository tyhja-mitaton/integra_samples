<?php

namespace Integra\Models\Ubet;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use Yii;

/**
 * @property int $bet_id
 * @property int $user_id
 * @property float $amount
 * @property string $dttm_at
 * @property int $game_id
 * @property string $provider_bet_id
 * @property int $type_id
 */
class GameBet extends ActiveRecord
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
        return '{{%game_bets}}';
    }
}