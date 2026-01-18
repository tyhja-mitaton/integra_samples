<?php

declare(strict_types=1);

namespace Integra\Models\Ubet;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property int         $id
 * @property string      $dttm_reg
 * @property bool        $status
 * @property int|null    $alanbase_partner_id
 * @property bool|null   $alanbase_status
 */
class UsersAffiliate extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_ubet');
    }

    public static function tableName(): string
    {
        return '{{%users_affiliate}}';
    }
}