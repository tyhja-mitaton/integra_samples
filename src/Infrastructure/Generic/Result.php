<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Generic;

interface Result
{
	public function isSuccessful(): bool;
	public function value(): array;
	public function error(): array;
}
