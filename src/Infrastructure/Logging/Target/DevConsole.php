<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Logging\Target;

use yii\helpers\VarDumper;
use yii\log\Logger;
use yii\log\Target;
use Throwable;

class DevConsole extends Target
{
    private $handler;

    public function init()
    {
        $this->handler = fopen('/dev/console', 'w');
        parent::init();
    }

    public function export()
    {
        foreach ($this->messages as $message) {
            fwrite($this->handler, $this->formatMessage($message) . PHP_EOL);
        }
    }

    public function formatMessage($message)
    {
        list($text, $level, $category, $timestamp) = $message;

        $level = Logger::getLevelName($level);

        if (!is_string($text)) {
            // exceptions may not be serializable if in the call stack somewhere is a Closure
            if ($text instanceof Throwable) {
                $text = (string) $text;
            } else {
                $text = VarDumper::export($text);
            }
        }

        $traces = '';

        if (isset($message[4])) {
            foreach ($message[4] as $trace) {
                $traces .= " in {$trace['file']}:{$trace['line']}";
            }
        }

        $prefix = $this->getMessagePrefix($message);

        return $this->getTime($timestamp) . " {$prefix}[$level][$category] $text" . $traces;
    }
}
