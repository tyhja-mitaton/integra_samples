<?php

declare(strict_types=1);

namespace Integra\UserStory\ExternalApplication\Affise\FirstDepositEvent\Validation;

use Integra\Infrastructure\Generic\Result;
use Integra\Infrastructure\Validation\Validatable;
use Integra\Infrastructure\Validation\Verifica\Verifica;

/**
 * Class ValidatedFirstDepositEventCommand
 *
 * @package Integra\UserStory\ExternalApplication\Affise\FirstDepositEvent\Validation
 */
class ValidatedFirstDepositEventCommand implements Validatable
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

        $validator->rule('required', ['affise_device_id', 'p_price', 'p_quantity', 'p_revenue', 'p_currency'])
            ->rule('uuid', ['affise_device_id']);

        $validator->stopOnFirstFail();

        return $validator->result();
    }
}
