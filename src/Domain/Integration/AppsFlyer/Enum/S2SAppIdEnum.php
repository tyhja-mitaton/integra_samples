<?php

namespace Integra\Domain\Integration\AppsFlyer\Enum;

use Integra\Domain\Enum\EnumInterface;

enum S2SAppIdEnum: string implements EnumInterface
{
    case DEPOSIT_ANDROID = 'kz.ubet.android.beta-AppGallery';
    case DEPOSIT_IOS = 'id1621859452';

    public function value(): string
    {
        return $this->value;
    }
}
