<?php

namespace Integra\Models\Feed;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use Yii;

/**
 * @property int $id
 * @property string $event_date_ticks_at
 */
class FeedMatch extends ActiveRecord
{
    /**
     * @throws InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('db_feed');
    }

    public static function tableName(): string
    {
        return '{{%feed_match}}';
    }
}