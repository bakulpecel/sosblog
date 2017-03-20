<?php

namespace App\Controllers;

/**
* 
*/
class HomeController extends Controller
{
    /**
    * Sample handler
    */
    public function index($request, $response)
    {
        return $this->view->render($response, 'home.twig');
    }
}