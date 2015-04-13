<?php

namespace Acme\HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View\View;

class DefaultController extends Controller
{
    public function helloAction($name)
    {
        $data = new User($name);
        $view = View::create();

        $view->setData($data);
        $view->setTemplate('AcmeHelloBundle:Default:hello.html.twig');

        return $view;
    }
}
