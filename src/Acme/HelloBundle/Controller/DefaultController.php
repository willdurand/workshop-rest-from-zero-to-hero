<?php

namespace Acme\HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View\View;
use Acme\HelloBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function helloAction(Request $request, $name)
    {
        $data = new User($name);
        $view = View::create();

        $view->setData($data);
        $view->setTemplate('AcmeHelloBundle:Default:hello.html.twig');

        return $view;
    }
}
