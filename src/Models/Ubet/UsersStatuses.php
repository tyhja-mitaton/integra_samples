<?php

declare(strict_types=1);

namespace Integra\Models\Ubet;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $status_id
 * @property string $name
 * @property double $lower
 * @property double $upper
 * @property int $cashback
 * @property string $money_type
 *
 * @property User $user
 * @property UsersStatuses $status
 */
class UsersStatuses extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_ubet');
    }

    public static function tableName(): string
    {
        return '{{%users_statuses}}';
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }
}