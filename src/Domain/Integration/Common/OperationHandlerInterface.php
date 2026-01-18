<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Common;

use Integra\Infrastructure\Generic\Result;

interface OperationHandlerInterface
{
    /**
     * @param array $rawParams
     * @return Result
     */
    public function execute(array $rawParams): Result;
}
