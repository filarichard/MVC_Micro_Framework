<?php

// urceni jmenneho prostoru
namespace MVC\Router;

// rozsiruje rozhrani RouterInterface
class Router implements RouterInterface
{
    // deklarace promenne
    private $routes = [];

    // pridani nove cesty
    public function addRoute(RouteInterface $route)
    {
        // pred samotnym vlozenim nove cesty, zajisti aby v poli nebyla vicekrat, tim ze ji nejprve odstrani
        $this->removeRoute($route->getName());
        $this->routes[] = $route;
    }

    // odstraneni cesty
    public function removeRoute(String $name)
    {
        foreach ($this->routes as $index => $route) {
            if ($route->getName() === $name) {
                unset($this->routes[$index]);
            }
        }
    }

    // zjisti shodu a vrati, v pripade ze neexistuje, vyvola vyjimku
    public function getMatch(String $name): RouteInterface
    {
        foreach ($this->routes as $index => $route) {
            if ($route->getName() === $name) {
                return $route;
            }
        }
        throw new RouteNotFoundException("Route " . $name . " doesn't exists!");
    }
}
