<?php

declare(strict_types=1);

namespace Integra\Models\Ubet;

use Yii;
use DateTime;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $log_id
 * @property int $user_id
 * @property string $login
 * @property int $ip
 * @property string $dttm
 * @property string|null $visitor_id
 * @property string|null $visitor_id_pro
 * @property string $device
 * @property string|null $mindbox_uuid
 * @property string|null $adjust_ad_uuid
 */
class UsersAuthHistory extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_ubet');
    }

    public static function tableName(): string
    {
        return '{{%users_auth_history}}';
    }
}