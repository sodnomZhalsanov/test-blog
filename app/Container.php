<?php

namespace App;

class Container
{
    private array $services = [];

    public function set(string $name, callable $callable): void
    {
        $this->services[$name] = $callable;
    }

    public function get(string $name): object
    {
        if (!isset($this->services[$name])) {
            return new $name();
        }

        $callback = $this->services[$name];

        return $callback($this);
    }

}