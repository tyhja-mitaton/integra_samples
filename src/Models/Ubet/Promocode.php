<?php
declare(strict_types=1);

namespace Integra\Models\Ubet;

use Yii;
use yii\db\ActiveRecord;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * @property integer $promocode_id
 * @property string $name
 * @property string $public_code
 * @property integer $parent_code_id
 * @property integer $user_id
 * @property integer $time_to_activation
 * @property integer $active_tm
 * @property float $wagering_amount
 *
 * @property User $user
 */
class Promocode extends ActiveRecord
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
        return '{{%promocodes}}';
    }

    /**
     * @return ActiveQuery|User
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['promocode_id' => 'promocode_id']);
    }
}