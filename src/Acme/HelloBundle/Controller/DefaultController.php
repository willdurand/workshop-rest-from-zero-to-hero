<?php

namespace Acme\HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class DefaultController extends Controller
{
    public function helloAction($name)
    {
        $data = [ 'name' => $name ];
        $view = View::create();

        $view->setData($data);
        $view->setTemplate('AcmeHelloBundle:Default:hello.html.twig');

        return $view;
    }
}
