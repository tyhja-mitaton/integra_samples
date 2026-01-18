<?php

declare(strict_types=1);

namespace Integra\Infrastructure;

use yii\base\Application as YiiApplication;

interface Application
{
	public function application(): YiiApplication;
}
