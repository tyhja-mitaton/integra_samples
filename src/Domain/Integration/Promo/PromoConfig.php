<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Promo;

use Exception;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Request\Url\FromString;
use Integra\Domain\Integration\Promo\Config\PromoOperationConfig;
use Integra\Domain\Integration\Promo\Config\PromoOperationConfigInterface;

final class PromoConfig
{
    private const DEFAULT_TIMEOUT_SECONDS = 10;

    /**
     * @param int|null $timeoutSeconds
     * @return PromoOperationConfigInterface
     * @throws Exception
     */
    public static function create(?int $timeoutSeconds = null): PromoOperationConfigInterface
    {
        $baseUrl = (new Env('UB_PROMO_SERVICE_URL'))->value();
        $token = (new Env('UB_PROMO_SERVICE_TOKEN'))->value();

        $url = new FromString(rtrim($baseUrl, '/') . '/v1/loyalty/process');

        return new PromoOperationConfig(
            url: $url,
            token: $token,
            timeoutSeconds: $timeoutSeconds ?? self::DEFAULT_TIMEOUT_SECONDS
        );
    }
}
