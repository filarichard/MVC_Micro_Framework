<?php

// urceni jmenneho prostoru
namespace EventDispatcher;

// implementace rozhrani ListenerProviderInterface
class ListenerProvider implements ListenerProviderInterface
{
    // deklarace promenne
    private $listeners = [];

    // prida novou udalost a posluchace
    public function add($listener, Event $event)
    {
        // zajisti unikatnost v poli ti, ze nejdriv hodnotu odebere
        $this->remove($listener, $event);
        $this->listeners[] = [
            "event" => $event,
            "listener" => $listener
        ];
    }

    // odstrani udalost a posluchace
    public function remove($listener, Event $event)
    {
        foreach ($this->listeners as $index => $item) {
            if ($item["event"] == $event & $item["listener"] == $listener)
            {
                unset($this->listeners[$index]);
            }
        }
    }

    // zjisti vsechny posluchace, kteri jsou k udalosti pripojeni
    public function getListenersForEvent(object $event) : iterable
    {
        $retVal = [];
        foreach ($this->listeners as $item) {
            if ($item["event"] == $event) {
                array_push($retVal, $item["listener"]);
            }
        }
        return $retVal;
    }
}
