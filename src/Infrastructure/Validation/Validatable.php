<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Validation;

use Integra\Infrastructure\Generic\Result;

interface Validatable
{
    public function result(): Result;
}
