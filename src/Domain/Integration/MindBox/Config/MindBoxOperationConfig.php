<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Config;

use Integra\Domain\Enum\PlatformNameEnum;
use Integra\Infrastructure\Http\Request\Url;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\MindBox\Enum\MindBoxOperationNameEnum;

/**
 *  Конфиг для MindBox-операций
 */
final class MindBoxOperationConfig implements MindBoxOperationConfigInterface
{
    public function __construct(
        private readonly Url                      $url,
        private readonly string                   $secretKey,
        private readonly MindBoxOperationNameEnum $operation,
//        private readonly PlatformNameEnum         $platform,
        private readonly int                      $timeoutSeconds,
    )
    {
    }

    /**
     * @return MindBoxOperationNameEnum
     */
    public function getOperationName(): MindBoxOperationNameEnum
    {
        return $this->operation;
    }

//    /**
//     * @return PlatformNameEnum
//     */
//    public function getPlatform(): PlatformNameEnum
//    {
//        return $this->platform;
//    }

    /**
     * @return int
     */
    public function getTimeoutSeconds(): int
    {
        return $this->timeoutSeconds;
    }

    /**
     * @param DTOInterface|null $dto
     * @return Url
     */
    public function getUrl(DTOInterface $dto = null): Url
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }
}
