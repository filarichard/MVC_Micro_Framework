<?php

// urceni jmenneho prostoru
namespace MVC;

use Models;

// abstraktni trida kontroler
abstract class Controller
{
    // deklarace promenne
    protected $view;

    // priprava view
    // zajisti aby view byla instance tridy Template
    // uzivatel ale i presto muze promennou view nastavit jako jakykoliv jiny soubor
    protected function setView()
    {
        $this->view = new Template();
    }
}
