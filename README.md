# mvc_micro_framework
### Simple MVC Micro Framework

mvc_micro_framework is simple and lightweight PHP micro framework, that allows you easy and fast build of MVC applications. It's simple and extensible!

## Minimum requires
mvc_micro_framework requires `PHP 7.1` or grater.

## Installation
1. Download

**With Composer:**  
`composer require mikecao/flight`

**Without Composer:**  
Download files and extract them directly to web directory.

2. Create **App** and call **run** function  
```php
$application = new \App\App();
$application->run();
```

3. Create **config.json** file in `/config` directory  
```json
{
  "routes": {
    "/third_parties_components": {
      "controller": "Controllers\\ThirdPartiesController",
      "action": "tpc"
    }
  },
  "modules": [
    "mvc"
  ],
  "diContainer": "config/diContainer.php"
}
```
Possible config values are:  
* routes - list of all routes used in app, that doesn't use modules.
* modules - list of all modules used in app.
* router - path to PHP fith configured router.
* diContainer - path to PHP fith configured DI Container.
* request - path to PHP fith configured HTTP Request.

## Routing
For fully working routing, use followed directory:
* config
* src
  * config
    * All configuration files for modules
    * modelname.config.json
  * controllers
    * All controllers
    * If defined, use Modul name
      * Modul Controllers
  * models
  * views

For Router setup, you can use on of the following choices:
1. Define routes in **config** file

All routes, that isn't included in modules, create in **config.json** file.  
All routes, that are in modules, create in **modulname.config.json** files.

Structure of route definition:
```json
"/nameOfRoute": {
    "controller": "Controllers\\NameOfController",
    "action": "nameofFunction"
  }
```

2. Define own **router** with all routes

```php
$router = new \MVC\Router();

$router->addRoute(new \MVC\Route("/nameOfRoute", \Controllers\NameOfController::class, "nameOfFunction"));

return $router;
```

## Template Engine
mvc_micro_framework have built in Template Engine. Just follow these few steps:
1. Set template

```php
$this->setView();
```
2. Assign variables

```php
$this->view->assignValue("nameOfVariable", $value);

$this->view->assignValue("title1", $titleValue);
$this->view->assignValue("subtitle1", $subtitleValue);
$this->view->assignValue("content1", $contentValue);
```
3. Use those variables in **HTML** code

```html
<h1>{title1}</h1>
<h2>{subtitle1}</h2>
<p>
  {content1}
</p>
```
4. Render View

```php
$this->view->render("pathToFile.html");
```
Function **render** can take second parameter, that define, if path is full or relative to `/src/views`.

## Event Dispatcher
With following steps, you can use our Event Dispatcher:
1. Create new Dispatcher

```php
$this->eventDispatcher = new Dispatcher();
```
2. Create new Event

```php
$this->eventName = new Event("eventName");
```
3. Attach him to Listener

```php
$this->eventDispatcher->attach($this->eventName, array($this, 'listenerName'));
```
Note: Listener can be any Callable object.

4. Dispatch Event

```php
$this->eventDispatcher->dispatch($this->eventName);
```

You can also remove Event
```php
$this->eventDispatcher->detach($this->eventName, array($this, 'listenerName'));
```
or stop event propagation
```php
$this->eventName->setPropagationStopped(true);
```
## Third Parties Components
mvc_micro_framework support following components from third parties:
* HTTP Foundation/Symfony
* Eloquent (ORM)/Laravel
* Pimple (DI Container)
* Monolog (Logger)

## License
Flight is released under the MIT license.
