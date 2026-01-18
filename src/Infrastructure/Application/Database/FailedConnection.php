<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application\Database;

use PDOException;

class FailedConnection
{
	public function __call($name, $arguments)
	{
		throw new PDOException('Emulated database error appears');
	}
}
