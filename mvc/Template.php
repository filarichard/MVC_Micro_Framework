<?php

// urceni jmenneho prostoru
namespace MVC;

// import use
use Symfony\Component\HttpFoundation\Response;

class Template
{
    // deklarace promenne
    private $vars = [];

    // funkce pro prirazeni vetsiho mnozstvi hodnot
    public function assignValues($vars)
    {
        array_push($this->vars, $vars);
    }

    // funkce pro prirazeni jedne hodnoty
    public function assignValue($key, $value)
    {
        $this->vars[$key] = $value;
    }

    // funkce, ktera vyhleda pozadovany soubor
    // pote v nem nahradi promenne ve specialnich znackach {} za prirazene hodnoty
    // vysledek vrati pomoci HTTP Response
    public function render($viewName, $status = 200, bool $fullPath = false)
    {
        // zjisti jestli je zvolena moznost plne cesty
        // potreba kdyz chce uzivatel vykreslovat pohledy z jine slozky nez src/views
        if ($fullPath) {
            $prefix = "";
        } else {
            $prefix = "src/views/";
        }
        // zjisti jestli soubor existuje, jestli ne, vyvola vyjimku
        if (file_exists($prefix . $viewName)) {
            // zajisti obsah stranky
            $content = file_get_contents($prefix . $viewName);

            // projde vsechny vyskyty hodnot ve specielnich znackach {} a nahradi je za prirazene hodnoty
            // pomoci vyuziti regularnich vyrazu
            foreach ($this->vars as $key => $value) {
                $content = preg_replace('/\{' . $key . '\}/', $value, $content);
            }

            // pripravi a odesle HTTP Response
            $response = new Response(
                'Content',
                $status,
                ['content-type' => 'text/html']
            );

            $response->setContent($content);
            $response->send();
        } else {
            throw new \LogicException("File not found!");
        }
    }
}
