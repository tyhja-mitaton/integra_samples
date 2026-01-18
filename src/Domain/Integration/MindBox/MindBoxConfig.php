<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox;

use Exception;
use Integra\Domain\Enum\PlatformNameEnum;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Request\Url\FromString;
use Integra\Domain\Integration\MindBox\Enum\MindBoxOperationTypeEnum;
use Integra\Domain\Integration\MindBox\Enum\MindBoxOperationNameEnum;
use Integra\Domain\Integration\MindBox\Config\MindBoxOperationConfig;
use Integra\Domain\Integration\MindBox\Config\MindBoxOperationConfigInterface;

final class MindBoxConfig
{
    private const DEFAULT_TIMEOUT_SECONDS = 10;

    /**
     * @param MindBoxOperationNameEnum $operation
     * @param PlatformNameEnum|null $platform
     * @param int|null $timeoutSeconds
     * @param MindBoxOperationTypeEnum|null $operationType
     * @return MindBoxOperationConfigInterface
     * @throws Exception
     */
    public static function create(
        MindBoxOperationNameEnum  $operation,
        ?PlatformNameEnum         $platform,
        ?int                      $timeoutSeconds = null,
        ?MindBoxOperationTypeEnum $operationType = null,
    ): MindBoxOperationConfigInterface
    {
        if (empty($operationType)) {
            $operationType = MindBoxOperationTypeEnum::SYNC;
        }

        $baseUrl = (new Env('UB_MINDBOX_OPERATIONS_SEND_URL'))->value() . '/' . $operationType->value();

        $secretVar = match ($platform) {
            PlatformNameEnum::WEB => 'UB_MINDBOX_WEB_SECRET_KEY',
            PlatformNameEnum::IOS => 'UB_MINDBOX_IOS_SECRET_KEY',
            PlatformNameEnum::ANDROID => 'UB_MINDBOX_ANDROID_SECRET_KEY',
            default => 'UB_MINDBOX_DEFAULT_SECRET_KEY',
        };
        $endpointVar = match ($platform) {
            PlatformNameEnum::WEB => 'UB_MINDBOX_WEB_ENDPOINT_ID',
            PlatformNameEnum::IOS => 'UB_MINDBOX_IOS_ENDPOINT_ID',
            PlatformNameEnum::ANDROID => 'UB_MINDBOX_ANDROID_ENDPOINT_ID',
            default => 'UB_MINDBOX_DEFAULT_ENDPOINT_ID',
        };

        $url = new FromString(rtrim($baseUrl, '/') . '?' .
            http_build_query([
                'endpointId' => (new Env($endpointVar))->value(),
                'operation' => $operation->value,
            ]));
        $secretKey = (new Env($secretVar))->value();

        return new MindBoxOperationConfig(
            url: $url,
            secretKey: $secretKey,
            operation: $operation,
//            platform: $platform,
            timeoutSeconds: $timeoutSeconds ?? self::DEFAULT_TIMEOUT_SECONDS,
        );
    }
}
