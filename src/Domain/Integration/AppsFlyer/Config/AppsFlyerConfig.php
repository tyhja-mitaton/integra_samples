<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\AppsFlyer\Config;

use Integra\Domain\Enum\PlatformNameEnum;
use Integra\Domain\Integration\AppsFlyer\Enum\S2SAppIdEnum;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Request\Url\FromString;
use DomainException;

final class AppsFlyerConfig
{
    private const DEFAULT_TIMEOUT_SECONDS = 10;

    /**
     * @param PlatformNameEnum $platform
     * @param int|null $timeoutSeconds
     * @return AppsFlyerOperationConfig
     * @throws \Exception
     */
    public static function create(PlatformNameEnum $platform, ?int $timeoutSeconds = null)
    {
        $baseUrl = rtrim((new Env('UB_APPSFLYER_S2S_URL'))->value(), '/');

        $appId = match ($platform) {
            PlatformNameEnum::ANDROID => S2SAppIdEnum::DEPOSIT_ANDROID,
            PlatformNameEnum::IOS => S2SAppIdEnum::DEPOSIT_IOS,
            default => throw new DomainException('Unsupported AppsFlyer platform: ' . $platform->value()),
        };
        $url = new FromString("{$baseUrl}/inappevent/" . $appId->value());

        return new AppsFlyerOperationConfig(
            url: $url,
            timeoutSeconds: $timeoutSeconds ?? self::DEFAULT_TIMEOUT_SECONDS,
            accessToken: (new Env('UB_APPSFLYER_ACCESS_TOKEN'))->value(),
        );
    }
}