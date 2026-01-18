<?php

declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\SportExpertRegisterUser\Handler;

use Integra\Domain\Enum\PlatformNameEnum;
use Integra\Infrastructure\Http\Transport;
use Integra\Domain\Services\User\CashbackService;
use Integra\Domain\Services\User\BlacklistService;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\MindBox\MindBoxConfig;
use Integra\Domain\Services\User\BackofficePlayerMarksService;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\MindBox\Enum\MindBoxOperationNameEnum;
use Integra\Domain\Integration\MindBox\Operation\AbstractMindBoxOperationHandler;
use Integra\Domain\Integration\MindBox\Operation\SportExpertRegisterUser\Request\RegisterCustomerRequest;
use Integra\Domain\Integration\MindBox\Operation\SportExpertRegisterUser\Data\SportExpertRegisterCustomerDataBuilder;

final class SportExpertRegisterCustomerHandler extends AbstractMindBoxOperationHandler
{
    private SportExpertRegisterCustomerDataBuilder $builder;

    public function __construct(
        Transport                    $transport,
        BlacklistService             $blacklistService,
        CashbackService              $cashbackService,
        BackofficePlayerMarksService $playerMarksService,
    )
    {
        parent::__construct($transport);

        $this->builder = new SportExpertRegisterCustomerDataBuilder(
            $blacklistService,
            $cashbackService,
            $playerMarksService,
        );
    }

    /**
     * @param array $raw
     * @return DTOInterface
     * @throws \Exception
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            userId: (int)$raw['userId'],
            landing: $raw['landing'] ?? null,
        );
    }

    /**
     * @param array $raw
     * @return IntegrationConfigInterface
     * @throws \Exception
     */
    protected function buildConfig(array $raw): IntegrationConfigInterface
    {
        $platform = PlatformNameEnum::tryFrom((string)$raw['deviceReg']) ?? null;
        $timeout = isset($raw['timeoutSeconds']) ? (int)$raw['timeoutSeconds'] : null;

        return MindBoxConfig::create(
            MindBoxOperationNameEnum::SPORT_EXPERT_REGISTER_USER,
            $platform,
            $timeout
        );
    }

    /**
     * @param DTOInterface $dto
     * @param IntegrationConfigInterface $config
     * @return RegisterCustomerRequest
     */
    protected function buildRequest(
        DTOInterface               $dto,
        IntegrationConfigInterface $config
    ): RegisterCustomerRequest
    {
        return new RegisterCustomerRequest($dto, $config);
    }
}
