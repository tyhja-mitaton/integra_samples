<?php

namespace Integra\Models\Ubet;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use Yii;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 */
class GameType extends ActiveRecord
{
    /**
     * @throws InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('db_ubet');
    }

    public static function tableName(): string
    {
        return '{{%game_types}}';
    }
}