<?php

declare(strict_types=1);

namespace Integra\UserStory\ExternalApplication\Affise\RegisterEvent\Validation;

use Integra\Infrastructure\Generic\Result;
use Integra\Infrastructure\Validation\Validatable;
use Integra\Infrastructure\Validation\Verifica\Verifica;

/**
 * Class ValidatedRegisterEventCommand
 *
 * @package Integra\UserStory\ExternalApplication\Affise\RegisterEvent\Validation
 */
class ValidatedRegisterEventCommand implements Validatable
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

        $validator->rule('required', ['affise_device_id', 'ubet_user_id'])
            ->rule('uuid', ['affise_device_id'])
            ->rule('string', ['ubet_user_id']);

        $validator->stopOnFirstFail();

        return $validator->result();
    }
}
