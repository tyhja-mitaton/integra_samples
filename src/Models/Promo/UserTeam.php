<?php

namespace Integra\Models\Promo;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Yii;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $team_id
 * @property float $win_factor
 * @property bool $is_active
 * @property string $expired_at
 * @property string $created_at
 *
 * @property Team $team
 */
class UserTeam extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_promo');
    }

    public static function tableName(): string
    {
        return '{{%user_team}}';
    }

    /**
     * @return ActiveQuery|Team
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}