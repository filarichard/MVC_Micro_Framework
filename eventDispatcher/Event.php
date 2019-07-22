<?php

// urceni jmenneho prostoru
namespace EventDispatcher;

// implementace StoppableEventInterface
class Event implements StoppableEventInterface
{
    // deklarace promennych
    private $propagationStopped = false;
    private $name;
    private $params;

    // klasicke nastaveni promennych pomoci konstruktoru a metod get a set

    public function __construct($name, $params = null)
    {
        $this->name = $name;
        $this->params = $params;
    }

    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

    public function setPropagationStopped(bool $propagationStopped)
    {
        $this->propagationStopped = $propagationStopped;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }
}
