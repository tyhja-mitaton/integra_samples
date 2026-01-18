<?php

declare(strict_types=1);

namespace Integra\Models\Ubet;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * todo
 */
class UsersPaymentCode extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_ubet');
    }

    public static function tableName()
    {
        return '{{%users_payment_codes}}';
    }
}