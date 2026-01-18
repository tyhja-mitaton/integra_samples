<?php

namespace Integra\Models\Ubet;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use Yii;

/**
 * @property int $pay_id
 * @property int $user_id
 * @property float $amount
 * @property string $currency
 * @property float $fee
 * @property string $type
 * @property string $dttm_begin
 * @property int $system_id
 * @property string $dttm_end
 *
 * @property-read User $user
 */
class Payment extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_ubet');
    }

    public static function tableName(): string
    {
        return '{{%payments}}';
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }
}