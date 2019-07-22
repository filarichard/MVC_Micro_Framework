<?php

namespace MVC\Router;

interface RouterInterface {

    public function addRoute(RouteInterface $route);
    public function removeRoute(String $name);
    public function getMatch(String $name): RouteInterface;
}
