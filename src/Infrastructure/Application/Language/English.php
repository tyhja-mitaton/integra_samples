<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application\Language;

use Integra\Infrastructure\Application\Language;

class English implements Language
{
	public function value(): string
	{
		return 'en';
	}
}
