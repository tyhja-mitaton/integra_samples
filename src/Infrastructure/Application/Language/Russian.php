<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application\Language;

use Integra\Infrastructure\Application\Language;

class Russian implements Language
{
	public function value(): string
	{
		return 'ru';
	}
}
