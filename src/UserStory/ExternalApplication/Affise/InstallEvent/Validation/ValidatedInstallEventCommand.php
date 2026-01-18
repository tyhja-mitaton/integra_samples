<?php

declare(strict_types=1);

namespace Integra\UserStory\ExternalApplication\Affise\InstallEvent\Validation;

use Integra\Infrastructure\Generic\Result;
use Integra\Infrastructure\Validation\Validatable;
use Integra\Infrastructure\Validation\Verifica\Verifica;

/**
 * Class ValidatedInstallEventCommand
 *
 * @package Integra\UserStory\ExternalApplication\Affise\InstallEvent\Validation
 */
class ValidatedInstallEventCommand implements Validatable
{
    /**
     * @param array $queryParams
     */
    public function __construct(private array $queryParams) {}

    /**
     * @return Result
     */
    public function result(): Result
    {
        $validator = new Verifica($this->queryParams);

        $validator->rule('required', ['gaid', 'affise_device_id', 'random_user_id'])
            ->rule('uuid', ['gaid', 'affise_device_id', 'random_user_id']);

        $validator->stopOnFirstFail();

        return $validator->result();
    }

}
