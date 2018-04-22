<?php
namespace Classes\Controller;

use Psr\Container\ContainerInterface;

abstract class Controller 
{
    protected $container;

    public function __construct(ContainerInterface $container) 
    {
        $this->container = $container;
    }
}