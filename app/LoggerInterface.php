<?php
namespace App;
interface LoggerInterface {
    public function error(string $message);

    public function debug(string $message);

    public function warning(string $message);
}
