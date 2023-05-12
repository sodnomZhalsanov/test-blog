<?php

namespace App;
use App\Exception\ContainerException;

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
            if (class_exists($name)) {
                return new $name();
            }
            throw new ContainerException();
        }

        $callback = $this->services[$name];

        return $callback($this);
    }

}