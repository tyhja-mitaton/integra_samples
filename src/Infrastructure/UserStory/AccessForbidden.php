<?php

declare(strict_types=1);

namespace Integra\Infrastructure\UserStory;

use Integra\Infrastructure\Generic\Response;
use Integra\Infrastructure\Generic\Response\Forbidden;
use Integra\Infrastructure\UserStory;

class AccessForbidden implements UserStory
{
    public function response(): Response
    {
        return new Forbidden();
    }
}
