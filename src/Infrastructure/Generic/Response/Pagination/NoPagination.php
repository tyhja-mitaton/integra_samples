<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Generic\Response\Pagination;

use Exception;
use Integra\Infrastructure\Generic\Response\Pagination;

class NoPagination implements Pagination
{
    public function isUsed(): bool
    {
        return false;
    }

    public function total(): int
    {
        throw $this->exception();
    }

    public function perPage(): int
    {
        throw $this->exception();
    }

    public function page(): int
    {
        throw $this->exception();
    }

    public function pages(): int
    {
        throw $this->exception();
    }

    private function exception(): Exception
    {
        return new Exception('Non used pagination has no active properties');
    }
}
