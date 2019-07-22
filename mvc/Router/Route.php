<?php

// urceni jmenneho prostoru
namespace MVC\Router;

// implementace RouterInterface
class Route implements RouteInterface
{
    // deklarace promennych
    private $name;
    private $class;
    private $action;

    // klasicke nastavovani hodnot pomoci konstruktoru a get a set metod

    public function __construct(String $name, String $controller, String $action)
    {
        $this->name = $name;
        $this->class = $controller;
        $this->action = $action;
    }

    public function getName(): String
    {
        return $this->name;
    }

    public function setName(String $name)
    {
        $this->name = $name;
    }

    public function getClass(): String
    {
        return $this->class;
    }

    public function setClass(String $class)
    {
        $this->class = $class;
    }

    public function getAction(): String
    {
        return $this->action;
    }

    public function setAction(String $action)
    {
        $this->action = $action;
    }
}
