<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Affise\Config;

use Exception;
use Integra\Infrastructure\Http\Request\Url;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Infrastructure\Http\Request\Url\FromString;

final class AffiseOperationConfig implements AffiseOperationConfigInterface
{
    public function __construct(
        private readonly Url    $url,
        private readonly string $token,
        private readonly int    $timeoutSeconds,
    )
    {
    }

    /**
     * @param DTOInterface|null $dto
     * @return Url
     * @throws Exception
     */
    public function getUrl(DTOInterface $dto = null): Url
    {
        if (empty($dto)) {
            return $this->url;
        }
        $base = rtrim($this->url->value(), '/');
        $params = array_merge(
            ['app_token' => $this->token],
            $dto->toArray()
        );

        return new FromString($base . '?' . http_build_query($params));
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getTimeoutSeconds(): int
    {
        return $this->timeoutSeconds;
    }

}
