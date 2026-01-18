<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application;

interface Database
{
	public function value(): array;
}
