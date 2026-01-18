<?php

namespace Integra\Models\Promo;

use yii\db\ActiveRecord;
use Yii;

/**
 * @property integer $id
 * @property integer $sport_id
 * @property integer $tournament_id
 * @property integer $championship_id
 * @property string $name
 */
class Team extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_promo');
    }

    public static function tableName(): string
    {
        return '{{%team}}';
    }
}