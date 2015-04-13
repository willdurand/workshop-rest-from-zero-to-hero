<?php

namespace Acme\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as REST;

class UserController extends FOSRestController
{
    /**
     * @REST\Get("/users.{_format}", defaults={"_format"="html"}),
     * @REST\View()
     */
    public function allAction()
    {
        $users = $this
            ->getDoctrine()
            ->getRepository('Acme\ApiBundle\Entity\User')
            ->findAll();

        return [ 'users' => $users ];
    }
}
