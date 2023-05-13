<?php

namespace App;
use App\Exception\ContainerException;

class Container
{
    private array $services = [];

    public function __construct(array $data)
    {
        $this->services = $data;
    }

    public function set(string $name, mixed $body): void
    {
        $this->services[$name] = $body;
    }

    public function get(string $name): mixed
    {
        if (!isset($this->services[$name])) {
            if (class_exists($name)) {
                return new $name();
            }
            throw new ContainerException("Class { $name } doesn't exist");
        }
        if (!is_callable($this->services[$name])) {
            return $this->services[$name];
        }

        $callback = $this->services[$name];

        return $callback($this);
    }

}