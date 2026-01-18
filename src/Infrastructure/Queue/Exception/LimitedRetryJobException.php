<?php
declare(strict_types=1);

namespace Integra\Infrastructure\Queue\Exception;

use DomainException;

/**
 * Ошибка, при которой Job нужно пробовать ограниченно раз (логическая ошибка, ответ внешнего сервиса 4xx и т.п.).
 */
class LimitedRetryJobException extends DomainException
{
}