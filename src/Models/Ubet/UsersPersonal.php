<?php

declare(strict_types=1);

namespace Integra\Models\Ubet;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $user_id
 * @property string $surname
 * @property string $firstname
 * @property string $lastname
 * @property string $birthday
 * @property string $sex
 * @property int $city_id
 * @property string $doc_type
 * @property string $doc_number
 * @property string $doc_dttm
 * @property string $iin
 *
 * @property User $user
 * /
 */
class UsersPersonal extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_ubet');
    }

    public static function tableName(): string
    {
        return '{{%users_personal}}';
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }
}