<?php

declare(strict_types=1);

namespace Integra\Models\Backoffice;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Integra\Models\Ubet\User;

/**
 * @property int $id
 * @property int $player_id
 * @property int $mark_id
 *
 * @property User $player
 *
*/
class PlayersMarks extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_backoffice');
    }

    public static function tableName(): string
    {
        return '{{%players_marks}}';
    }

    public function rules(): array
    {
        return [
            [['player_id','mark_id'], 'integer'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id'        => 'ID',
            'player_id' => 'Player ID',
            'mark_id'   => 'Mark ID',
        ];
    }

    public function getPlayer(): ActiveQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'player_id']);
    }
}