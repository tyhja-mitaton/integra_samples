<?php

declare(strict_types=1);

namespace Integra\Infrastructure\UserStory;

use Integra\Infrastructure\Generic\Response;
use Integra\Infrastructure\Generic\Response\Unauthorized;
use Integra\Infrastructure\UserStory;

class UserNotAuthorized implements UserStory
{
    public function response(): Response
    {
        return new Unauthorized();
    }
}
