<?php
declare(strict_types=1);

namespace Integra\Infrastructure\Queue\Exception;

use RuntimeException;

/**
 * Ошибка, при которой Job нужно пробовать **вечно** (сетевая ошибка, отказ брокера и т.п.).
 */
class EndlessRetryJobException extends RuntimeException
{
}