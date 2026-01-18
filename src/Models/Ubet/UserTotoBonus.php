<?php

namespace Integra\Models\Ubet;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use Yii;

/**
 * @property int $request_id
 * @property int $user_id
 * @property int $toto_bonus_id
 * @property int $promocode_id
 * @property int $bonus_id
 * @property string $status
 * @property float $amount
 * @property string $create_dttm
 * @property string $end_dttm
 * @property int $bonus_type
 * @property string $activ_dttm
 * @property float $min_betFactor
 * @property string $allowedBetTypeID
 * @property float $wageringTurnover
 */
class UserTotoBonus extends ActiveRecord
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
        return '{{%users_toto_bonuses}}';
    }
}