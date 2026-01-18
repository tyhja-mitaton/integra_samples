<?php

namespace Integra\Models\Ubet;

use yii\db\ActiveRecord;
use Yii;

/**
 * @property int $system_id
 * @property string $name
 */
class PaymentSystem extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_ubet');
    }

    public static function tableName(): string
    {
        return '{{%payments_system}}';
    }
}