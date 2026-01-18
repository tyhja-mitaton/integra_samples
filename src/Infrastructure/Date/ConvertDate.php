<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Date;

use DateTime;
use DateTimeZone;
use Integra\Infrastructure\Datetime\UTC;

class ConvertDate
{
    public function __construct(
        private $time,
        private DateTimeZone $timezone
    ){}

    public function value(): array
    {
        $startedDt = new DateTime($this->time, new UTC());

        return [
            'date' => $startedDt->format('Y-m-d\TH:i:s'),
            'time' => ''
        ];
    }
}
