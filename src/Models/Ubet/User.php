<?php

declare(strict_types=1);

namespace Integra\Models\Ubet;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeTypecastBehavior;

/**
 * @property integer $user_id
 * @property string $device_reg
 * @property string $dttm_reg
 * @property integer $channel_id
 * @property string $phone
 * @property integer $promocode_id
 * @property integer $invited_user_id
 * @property bool $promocode_restriction
 * @property bool $language_id
 * @property string $a_click_id
 * @property integer $a_partner_id
 * @property integer $a_offer_id
 * @property bool $real_bonus_is_active
 * @property bool $invited_active
 * @property bool $is_blocked
 * @property bool $is_approved
 * @property bool $is_notice_sms
 * @property bool $is_notice_email
 * @property bool $is_notice_push
 * @property string $blocked_till
 * @property string $email
 * @property string $invited_code
 * @property float $money_real
 * @property string $dttm_approve
 * @property string $first_deposit_dttm
 * @property boolean $email_active
 * @property bool $to_mindbox
 * @property bool $need_activ
 *
 * @property Channel $channel
 * @property User $friend
 * @property User[] $friends
 * @property Promocode $promocode
 * @property UsersPersonal $personal
 */
class User extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_ubet');
    }

    public static function tableName(): string
    {
        return '{{%users}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => AttributeTypecastBehavior::class,
                'attributeTypes' => [
                    'promocode_restriction' => AttributeTypecastBehavior::TYPE_BOOLEAN,
                    'language_id' => AttributeTypecastBehavior::TYPE_BOOLEAN,
                    'real_bonus_is_active' => AttributeTypecastBehavior::TYPE_BOOLEAN,
                    'invited_active' => AttributeTypecastBehavior::TYPE_BOOLEAN,
                    'is_blocked' => AttributeTypecastBehavior::TYPE_BOOLEAN,
                    'is_approved' => AttributeTypecastBehavior::TYPE_BOOLEAN,
                    'is_notice_sms' => AttributeTypecastBehavior::TYPE_BOOLEAN,
                    'is_notice_email' => AttributeTypecastBehavior::TYPE_BOOLEAN,
                    'is_notice_push' => AttributeTypecastBehavior::TYPE_BOOLEAN,
                ],
                'typecastAfterFind' => true,
                'typecastBeforeSave' => true,
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [
                [
                    'promocode_restriction',
                    'language_id',
                    'real_bonus_is_active',
                    'invited_active', 'is_blocked',
                    'is_approved',
                    'is_notice_sms',
                    'is_notice_email',
                    'is_notice_push'
                ], 'boolean',
                'trueValue' => 1, 'falseValue' => 0,
            ],
            ['blocked_till', 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @return ActiveQuery|Channel
     */
    public function getChannel(): ActiveQuery|Channel
    {
        return $this->hasOne(Channel::class, ['channel_id' => 'channel_id']);
    }

    public function getFriends(): ActiveQuery
    {
        return $this->hasMany(self::class, ['invited_user_id' => 'user_id']);
    }

    public function getFriend(): ActiveQuery
    {
        return $this->hasOne(self::class, ['user_id' => 'invited_user_id']);
    }

    public function getPromocode(): ActiveQuery|Promocode
    {
        return $this->hasOne(Promocode::class, ['promocode_id' => 'promocode_id']);
    }

    public function getPersonal(): ActiveQuery|UsersPersonal
    {
        return $this->hasOne(UsersPersonal::class, ['user_id' => 'user_id']);
    }
}