<?php

namespace Integra\Models\Ubet;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use Yii;

/**
 * @property int $id
 * @property int $user_id
 * @property int $bonus_id
 * @property int $transaction_id
 * @property float $amount_real
 */
class TransactionBonus extends ActiveRecord
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
        return '{{%transaction_bonus}}';
    }
}