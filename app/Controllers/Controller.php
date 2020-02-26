<?php


namespace App\Controllers;

class Controller
{
    public function abort404($response)
    {
        $response->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('Page not found');
            die;
    }

}