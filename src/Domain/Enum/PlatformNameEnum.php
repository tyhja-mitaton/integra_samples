<?php
declare(strict_types=1);

namespace Integra\Domain\Enum;

/**
 * Платформы регистрации пользователя.
 */
enum PlatformNameEnum: string implements EnumInterface
{
    case IOS = 'iOS';
    case ANDROID = 'android';
    case WEB = 'web';

    public function value(): string
    {
        return $this->value;
    }
}
