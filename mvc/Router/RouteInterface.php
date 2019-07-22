<?php

namespace MVC\Router;

interface RouteInterface {

    public function __construct(String $name, String $class, String $action);

    public function getName(): String;
    public function setName(String $name);
    public function getClass(): String;
    public function setClass(String $class);
    public function getAction(): String;
    public function setAction(String $class);
}
