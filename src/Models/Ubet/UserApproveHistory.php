<?php

namespace Integra\Models\Ubet;

use yii\db\ActiveRecord;
use Yii;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $close_dttm
 * @property string $req_dttm
 */
class UserApproveHistory extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_ubet');
    }

    public static function tableName(): string
    {
        return '{{%users_approve_history}}';
    }
}