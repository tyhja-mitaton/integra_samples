<?php

declare(strict_types=1);

namespace Integra\Models\Ubet;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property int $status_id
 * @property string $progress
 * @property string $dttm
 * @property float $bet_sum
 * @property float $bet_need
 *
 * @property User $user
 * @property UsersStatuses $status
 */
class UsersStatusCurrent extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_ubet');
    }

    public static function tableName(): string
    {
        return '{{%users_status_current}}';
    }
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(UsersStatuses::class, ['status_id' => 'status_id']);
    }
}