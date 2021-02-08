<?php

namespace Engage;

use Engage\Resources\Users as Users;

class ResourceFactory
{
    private $clientObj;
    private $resources = [
    'users' => Users::class,
  ];

    public function __construct($clientObj)
    {
        $this->clientObj = $clientObj;
    }

    public function __get($name)
    {
        if (!array_key_exists($name, $this->resources)) {
            throw new \InvalidArgumentException("The resource $name is not valid.");
        }

        $class = $this->resources[$name];

        return new $class($this->clientObj);
    }
}
