<?php

// urceni jmenneho prostoru
namespace MVC;

// import use
use MVC\Router\RouterInterface;
use Symfony\Component\HttpFoundation\Request;

class FrontController
{
    // deklarace promennych
    private $params = [];
    private $router;
    private $diContainer;

    // verejny konstruktor
    // vyzaduje na vstupu Router a Request
    // umoznuje vlozit vlastni DI kontejner
    public function __construct(RouterInterface $router, $request, $diContainer = null)
    {
        $this->router = $router;
        $this->diContainer = $diContainer;
        $this->route($request);
    }

    // metoda co vyhleda a zavola pozadovanou akci
    public function route(Request $request)
    {
        // zjisti nazev cesty podle informace z HTTP Request
        $name = $request->getPathInfo();

        // zjisti vsechny parametry predane v HTTP Request
        $this->params = $request->query->all();
        array_splice($this->params, 0, 1);

        // vyhleda v routeru spravnou cestu
        $route = $this->router->getMatch($name);

        // jestli pozadovana trida neexistuje, vyvola vyjimku
        $class = $route->getClass();
        if (!class_exists($class)) {
            throw new \LogicException("Class " . $class . " doesn't exists!");
        }

        // zajisti pozadovane parametry pro vytvoreni konstruktoru
        $reflection = new \ReflectionClass($class);
        $params = $this->prepareParams($reflection->getConstructor());
        // vytvori konstruktor
        $instance = $reflection->newInstanceArgs($params);

        // zjisti pozadovanou akci, nebo-li metodu
        // jestli metoda neexistuje, vyvola vyjimku
        $action = $route->getAction();
        if (!method_exists($instance, $action)) {
            throw new \BadFunctionCallException("Function " . $action . " doesn't exists!");
        }
        // zajisti pozadovane parametry metody
        $methodReflector = new \ReflectionMethod($instance, $action);
        $params = $this->prepareParams($methodReflector);

        // zavola metodu prislusneho ovladace, se zadanymi parametry
        call_user_func_array(array($instance, $action), $params);
    }

    // metoda pro pripravu parametru
    public function prepareParams(\ReflectionMethod $method)
    {
        // zjisti pozadovane parametry a pocet pozadovanych parametru
        $parameters = $method->getParameters();
        $reqNum = $method->getNumberOfRequiredParameters();
        $retVal = [];
        // projde vsechny pozadovane parametry a zjisti jestli existuji v DI kontejneru nebo v parametrech predanych
        // v HTTP Request
        // jestli ne, vyhodi vyjimku
        for ($i = 0; $i < sizeof($parameters); $i++) {
            if (isset($this->diContainer[$parameters[$i]->getName()])) {
                $retVal[] = $this->diContainer[$parameters[$i]->getName()];
            } elseif (isset($this->params[$parameters[$i]->getName()])) {
                $retVal[] = $this->params[$parameters[$i]->getName()];
            } elseif ($i+1 <= $reqNum) {
                throw new \InvalidArgumentException("Required parameters doesn't match" .
                    " given parameters! Missing parameter: " . $parameters[$i]);
            }
        }
        return $retVal;
    }
}
