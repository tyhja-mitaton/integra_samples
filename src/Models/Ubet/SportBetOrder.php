<?php

namespace Integra\Models\Ubet;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use Yii;

/**
 * @property int $order_id
 * @property int $bet_id
 * @property string $eventName
 * @property string $fullStake
 * @property string $eventNameOnly
 * @property int $stakeId
 * @property int $sportID
 * @property int $eventId
 * @property string $tournamentName
 * @property bool $isLive
 * @property string $sportName
 * @property int $stakeStatus
 */
class SportBetOrder extends ActiveRecord
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
        return '{{%sport_bets_order}}';
    }
}