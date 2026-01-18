<?php

declare(strict_types=1);

namespace Integra\Routes\Api;

use Integra\Infrastructure\Application\Routes;

class ApiV1 implements Routes
{
    public function value(): array
    {
        return [
            'GET,OPTIONS /api/v1/affise/events/install' => 'affise-event/install',
            'GET,OPTIONS /api/v1/affise/events/register' => 'affise-event/register',
            'GET,OPTIONS /api/v1/affise/events/deposit' => 'affise-event/deposit',
            'GET,OPTIONS /api/v1/affise/events/first-deposit' => 'affise-event/first-deposit',
            'GET,OPTIONS /api/v1/affise/events/bet' => 'affise-event/bet',
        ];
    }
}
