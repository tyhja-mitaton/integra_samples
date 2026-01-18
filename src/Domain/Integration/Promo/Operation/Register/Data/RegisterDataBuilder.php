<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Promo\Operation\Register\Data;

use Yii;
use DomainException;
use yii\helpers\StringHelper;
use Integra\Models\Ubet\User;
use Integra\Domain\Integration\Promo\Enum\TypeIdEnum;
use Integra\Domain\Integration\Promo\Operation\Register\DTO\RegisterPromoDTO;

final class RegisterDataBuilder
{
    /**
     * @param int $userId
     * @return RegisterPromoDTO
     */
    public function build(int $userId): RegisterPromoDTO
    {
        $user = User::findOne($userId);

        if (empty($user)) {
            $message = sprintf(
                '[%s] User not found:: %s',
                StringHelper::basename(self::class),
                $userId
            );
            Yii::error($message, __METHOD__);
            throw new DomainException($message);
        }

        return new RegisterPromoDTO(
            userId: $user->user_id,
            typeId: (int)TypeIdEnum::REGISTRATION->value(),
        );
    }
}