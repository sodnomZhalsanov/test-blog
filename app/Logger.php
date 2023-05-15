<?php

namespace App;

class Logger implements LoggerInterface
{
    public function error(string $message, array $context)
    {
        // TODO: Implement error() method.
        $mes = "{$message}. {$context['exception']} in {$context['file'] } on {$context['line']} line.";

        file_put_contents(__DIR__."/Log/error.php", $mes . PHP_EOL, FILE_APPEND);

    }

    public function debug(string $message, array $context)
    {
        // TODO: Implement debug() method.
        file_put_contents(__DIR__."/Log/debug.php", $message . PHP_EOL, FILE_APPEND);
    }

    public function warning(string $message, array $context)
    {
        // TODO: Implement warning() method.
        file_put_contents(__DIR__."/Log/warning.php", $message . PHP_EOL, FILE_APPEND);
    }

}