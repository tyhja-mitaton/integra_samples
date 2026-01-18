<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Affise;

use Exception;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Request\Url\FromString;
use Integra\Domain\Integration\Affise\Config\AffiseOperationConfig;
use Integra\Domain\Integration\Affise\Config\AffiseOperationConfigInterface;

/**
 * Создает конфиг Affise из ENV.
 */
final class AffiseConfig
{
    private const DEFAULT_TIMEOUT_SECONDS = 10;

    /**
     * @param int|null $timeoutSeconds
     * @return AffiseOperationConfigInterface
     * @throws Exception
     */
    public static function create(?int $timeoutSeconds = null): AffiseOperationConfigInterface
    {
        $baseUrl = (new Env('UB_AFFISE_URL'))->value();
        $token = (new Env('UB_AFFISE_TOKEN'))->value();

        $url = new FromString(rtrim($baseUrl, '/') . '/v1/affise/events/register');

        return new AffiseOperationConfig(
            url: $url,
            token: $token,
            timeoutSeconds: $timeoutSeconds ?? self::DEFAULT_TIMEOUT_SECONDS,
        );
    }
}
