<?php

// urceni jmenneho prostoru
namespace App;

// import use
use MVC\FrontController;
use MVC\Router\Route;
use MVC\Router\RouteNotFoundException;
use MVC\Router\Router;
use MVC\Template;
use Symfony\Component\HttpFoundation\Request;

class App
{
    // deklarace promennych
    private $router;
    private $request;
    private $diContainer;

    // konstruktor, ktery priradi zjisti jestli existuje soubor config a jestli ne, vytvori nove instance
    // router a request
    // dale kontroluje jestli pri se pri prirazeni promennych nevyvolaji vyjimky
    public function __construct()
    {
        try {
            if (!file_exists("config/config.json")) {
                $this->router = new Router();
                $this->request = Request::createFromGlobals();
            } else {
                $this->prepare();
            }
        }
        catch (\Exception $e) {
            $this->handleExceptions($e);
        }
    }

    // vytvori novou instanci front controlleru
    // zachyti vsechny vyjimky, ktere budou vyvolane pri behu front controlleru
    public function run()
    {
        try {
            $frontController = new FrontController($this->router, $this->request, $this->diContainer);
        }
        catch (RouteNotFoundException $e) {
            $this->handleExceptions($e, 404);
        }
        catch (\Exception $e) {
            $this->handleExceptions($e);
        }
    }

    // vsechny vyvolane vyjimky zachyti a zobrazi v instanci tridy Template
    private function handleExceptions(\Exception $e, $status = 500)
    {
        if ($this->diContainer != null) {
            if (isset($this->diContainer["logger"])) {
                $this->diContainer["logger"]->critical($e);
            }
        }
        $template = new Template();
        $template->assignValue("exception", $e->getMessage());
        $template->assignValue("content", $e->getTraceAsString());
        $template->render("vendor/filarichard/mvc_micro_framework/config/error.html", $status,true);
    }

    // priprava routeru, cest, requestu a DI kontejneru
    private function prepare()
    {
        $jsonFile = file_get_contents("config/config.json");
        $config = json_decode($jsonFile, true);
        if (array_key_exists("router", $config)) {
            $this->router = include $config["router"];
        } else {
            $this->router = new Router();
            $this->loadModules($config);
            $this->loadRoutes($config);
        }

        $this->loadRequest($config);
        $this->loadDiContainer($config);
    }

    // zjisti vsechny moduly definovane v konfiguracnim souboru
    // pote vyhleda konfiguracni soubor kazdeho modulu a vlozi vsechny cesty ktere jsou v nem ulozene
    private function loadModules($config)
    {
        if (array_key_exists("modules", $config)) {
            foreach ($config["modules"] as $module) {
                if (file_exists("src/config/" . $module . ".config.json")) {
                    $jsonFile = file_get_contents("src/config/" . $module . ".config.json");
                    $moduleConfig = json_decode($jsonFile, true);
                    if (array_key_exists("routes", $moduleConfig)) {
                        foreach ($moduleConfig["routes"] as $name => $route) {
                            $this->router->addRoute(new Route($name, $route["controller"], $route["action"]));
                        }
                    }
                } else {
                    throw new \LogicException("Cannot find config file!");
                }
            }
        }
    }

    // nahraje vsechny cesty ulozene v zakladnim konfiguracnim souboru
    // nejsou v zadnem modulu
    private function loadRoutes($config)
    {
        if (array_key_exists("routes", $config)) {
            foreach ($config["routes"] as $name => $route) {
                $this->router->addRoute(new Route($name, $route["controller"], $route["action"]));
            }
        }
    }

    // nahraje uzivatelem vytvoreny request, ktery neni v DI kontejneru
    private function loadRequest($config)
    {
        if (array_key_exists("request", $config)) {
            $this->request = include $config["request"];
        }
    }

    // nahraje DI kontejner a pripadne nahraje request z DI kontejneru
    private function loadDiContainer($config)
    {
        if (array_key_exists("diContainer", $config)) {
            $this->diContainer = include $config["diContainer"];
            if ($this->request === null && isset($this->diContainer["request"])) {
                $this->request = $this->diContainer["request"];
            } else {
                $this->request = Request::createFromGlobals();
            }
        }
    }
}
