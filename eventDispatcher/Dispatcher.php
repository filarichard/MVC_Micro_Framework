<?php

// urceni jmenneho prostoru
namespace EventDispatcher;

// implementace rozhrani EventDispatcherInterface
class Dispatcher implements EventDispatcherInterface
{
    // deklarace promenne
    private $listeners;

    public function __construct()
    {
        $this->listeners = new ListenerProvider();
    }

    // prirazeni posluchace k udalosti
    public function attach(Event $event, callable $listener)
    {
        $this->listeners->add($listener, $event);
    }

    // odpoutani posluchace od udalosti
    public function detach(Event $event, callable $listener)
    {
        $this->listeners->remove($listener, $event);
    }

    // informovani posluchacu o spustene udalosti
    public function dispatch(object $event)
    {
        // kontrola jestli je zapnuta propagace
        if (!$event->isPropagationStopped()) {
            foreach ($this->listeners->getListenersForEvent($event) as $listener) {
                call_user_func($listener);
            }
        }
    }
}
