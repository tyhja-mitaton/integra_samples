<?php

namespace Integra\Models\Ubet;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use Yii;

/**
 * @property int $id
 * @property string $name
 * @property int $bonus_type
 * @property float $amount
 * @property float $max_amount
 * @property int $period_wait
 * @property int $period_activ
 * @property int $wageringTurnover
 */
class TotoBonus extends ActiveRecord
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
        return '{{%toto_bonuses}}';
    }
}