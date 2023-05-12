<?php

namespace App;

class Logger implements LoggerInterface
{
    public function error(string $message)
    {
        // TODO: Implement error() method.

        file_put_contents(__DIR__."/Log/error.php", $message . PHP_EOL, FILE_APPEND);
    }

    public function debug(string $message)
    {
        // TODO: Implement debug() method.
        file_put_contents(__DIR__."/Log/debug.php", $message . PHP_EOL, FILE_APPEND);
    }

    public function warning(string $message)
    {
        // TODO: Implement warning() method.
        file_put_contents(__DIR__."/Log/warning.php", $message . PHP_EOL, FILE_APPEND);
    }

}