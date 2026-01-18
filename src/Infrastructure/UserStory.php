<?php

declare(strict_types=1);

namespace Integra\Infrastructure;

use Integra\Infrastructure\Generic\Response;

/**
 * Interface UserStory
 *
 * @package Integra\Infrastructure
 */
interface UserStory
{
    /**
     * @return Response
     */
    public function response(): Response;
}
