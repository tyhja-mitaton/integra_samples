<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Datetime;

class Asia extends \DateTimeZone
{
    public function __construct()
    {
        parent::__construct('Asia/Atyrau');
    }
}
