<?php

namespace Integra\Domain\Services;

use Integra\Domain\Enum\PlatformNameEnum;
use Integra\Models\Ubet\User;

final class AppsFlyerService
{
    /**
     * Проверяет, существуют ли в AppsFlyer данные для пользователя и зарегистрировался с мобильного устройства.
     * @param int $userId
     * @return bool
     */
    public function isAppsFlyerMobileUser(int $userId): bool
    {
        $user = User::findOne(['user_id' => $userId]);
        if(isset($user)) {
            if(in_array($user->device_reg, [PlatformNameEnum::IOS->value, PlatformNameEnum::ANDROID->value])) {
                return true;
            }
        }
        return false;
    }
}