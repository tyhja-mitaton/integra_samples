<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Adjust;

use Exception;
use Integra\Domain\Enum\PlatformNameEnum;
use Integra\Domain\Integration\Adjust\Enum\S2SEventEnum;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Request\Url\FromString;
use Integra\Domain\Integration\Adjust\Config\AdjustOperationConfig;
use Integra\Domain\Integration\Adjust\Config\AdjustOperationConfigInterface;

final class AdjustConfig
{
    private const DEFAULT_TIMEOUT_SECONDS = 10;

    /**
     * @param S2SEventEnum $eventToken
     * @param PlatformNameEnum $platform
     * @param int|null $timeoutSeconds
     * @return AdjustOperationConfigInterface
     * @throws Exception
     */
    public static function create(
        S2SEventEnum $eventToken,
        PlatformNameEnum $platform,
        ?int $timeoutSeconds = null
    ): AdjustOperationConfigInterface {
        $baseUrl = rtrim((new Env('UB_ADJUST_S2S_URL'))->value(), '/');
        $url = new FromString("{$baseUrl}/event");

        $appTokenVar = match ($platform) {
            PlatformNameEnum::ANDROID => 'UB_ADJUST_APP_TOKEN_ANDROID',
            PlatformNameEnum::IOS => 'UB_ADJUST_APP_TOKEN_IOS',
        };

        $bearerTokenVar = match ($platform) {
            PlatformNameEnum::ANDROID => 'UB_ADJUST_S2S_EVENTS_BEARER_TOKEN_ANDROID',
            PlatformNameEnum::IOS => 'UB_ADJUST_S2S_EVENTS_BEARER_TOKEN_IOS',
        };

        return new AdjustOperationConfig(
            url: $url,
            timeoutSeconds: $timeoutSeconds ?? self::DEFAULT_TIMEOUT_SECONDS,
            appToken: (new Env($appTokenVar))->value(),
            bearerToken: (new Env($bearerTokenVar))->value(),
            eventToken: $eventToken,
            environment: (new Env('UB_ADJUST_ENVIRONMENT'))->value()
        );
    }
}