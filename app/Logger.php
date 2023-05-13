<?php

namespace App;

class Logger implements LoggerInterface
{
    public function error(string $message, array $context)
    {
        // TODO: Implement error() method.

        file_put_contents(__DIR__."/Log/error.php", $message . PHP_EOL, FILE_APPEND);
        file_put_contents(__DIR__."/Log/error.php", $context['exception' ] . PHP_EOL, FILE_APPEND);
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