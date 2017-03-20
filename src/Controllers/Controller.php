<?php

namespace App\Controllers;

/**
* 
*/
abstract class Controller
{
    private $container;

    /**
    * All of the registered container
    */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
    * Dynamically access container
    */
    public function __get($property)
    {
        if ($this->container->{$property}) {
            return $this->container->{$property};
        }
    }
}