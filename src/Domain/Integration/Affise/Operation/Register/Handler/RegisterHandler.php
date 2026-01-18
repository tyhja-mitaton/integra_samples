<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Affise\Operation\Register\Handler;

use Exception;
use Integra\Infrastructure\Http\Transport;
use Integra\Domain\Integration\Affise\AffiseConfig;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\Affise\Config\AffiseOperationConfigInterface;
use Integra\Domain\Integration\Affise\Operation\AbstractAffiseOperationHandler;
use Integra\Domain\Integration\Affise\Operation\Register\Request\RegisterRequest;
use Integra\Domain\Integration\Affise\Operation\Register\Data\RegisterDataBuilder;

/**
 * Handler для отправки registration в Affise.
 */
final class RegisterHandler extends AbstractAffiseOperationHandler
{
    private RegisterDataBuilder $builder;

    public function __construct(Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new RegisterDataBuilder();
    }

    /**
     * @param array $rawParams
     * @return DTOInterface
     */
    protected function buildDTO(array $rawParams): DTOInterface
    {
        return $this->builder->build(
            affiseDeviceId: (string)$rawParams['affiseDeviceId'],
            userId: (int)$rawParams['userId'],
        );
    }

    /**
     * @param array $rawParams
     * @return AffiseOperationConfigInterface
     * @throws Exception
     */
    protected function buildConfig(array $rawParams): AffiseOperationConfigInterface
    {
        return AffiseConfig::create(
            isset($raw['timeoutSeconds']) ? (int)$raw['timeoutSeconds'] : null
        );
    }

    /**
     * @param DTOInterface $dto
     * @param AffiseOperationConfigInterface $config
     * @return RequestInterface
     */
    protected function buildRequest(
        DTOInterface                   $dto,
        AffiseOperationConfigInterface $config
    ): RequestInterface
    {
        return new RegisterRequest(
            dto: $dto,
            config: $config
        );
    }
}