<?php

declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\AuthorizeCustomer\Handler;

use Exception;
use Integra\Domain\Enum\PlatformNameEnum;
use Integra\Infrastructure\Http\Transport;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\MindBox\MindBoxConfig;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\MindBox\Enum\MindBoxOperationNameEnum;
use Integra\Domain\Integration\MindBox\Operation\AbstractMindBoxOperationHandler;
use Integra\Domain\Integration\MindBox\Operation\AuthorizeCustomer\Request\AuthorizeCustomerRequest;
use Integra\Domain\Integration\MindBox\Operation\AuthorizeCustomer\Data\AuthorizeCustomerDataBuilder;
use Integra\Models\Ubet\UsersAuthHistory;

final class AuthorizeCustomerHandler extends AbstractMindBoxOperationHandler
{
    private AuthorizeCustomerDataBuilder $builder;

    public function __construct(
        Transport $transport
    )
    {
        parent::__construct($transport);
        $this->builder = new AuthorizeCustomerDataBuilder();
    }

    /**
     * @param array $raw
     * @return DTOInterface
     * @throws Exception
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            $raw['userAuthHistory'],
            $raw['userAgent'] ?? null
        );
    }

    /**
     * @param array $raw
     * @return IntegrationConfigInterface
     * @throws Exception
     */
    protected function buildConfig(array $raw): IntegrationConfigInterface
    {
        /** @var UsersAuthHistory|null $userAuthHistoryModel */
        $userAthHistoryModel = $raw['userAuthHistory'];
        $platform = PlatformNameEnum::tryFrom((string)$userAthHistoryModel?->device) ?? null;

        $operation = match ($platform) {
            PlatformNameEnum::ANDROID,
            PlatformNameEnum::IOS => MindBoxOperationNameEnum::AUTH_FROM_MOBILE,
            default => MindBoxOperationNameEnum::AUTH_FROM_WEB,
        };
        $timeout = isset($raw['timeoutSeconds']) ? (int)$raw['timeoutSeconds'] : null;

        return MindBoxConfig::create(
            $operation,
            $platform,
            $timeout
        );
    }

    /**
     * @param DTOInterface $dto
     * @param IntegrationConfigInterface $config
     * @return RequestInterface
     */
    protected function buildRequest(
        DTOInterface               $dto,
        IntegrationConfigInterface $config
    ): RequestInterface
    {
        return new AuthorizeCustomerRequest($dto, $config);
    }
}