<?php

namespace Acme\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Acme\ApiBundle\Entity\UserCollection;
use Symfony\Component\HttpFoundation\Request;
use Acme\ApiBundle\Form\Type\UserType;
use Acme\ApiBundle\Entity\User;

class UserController extends FOSRestController
{
    /**
     * @REST\Get("/users.{_format}", defaults={"_format"="html"})
     * @REST\View()
     * @REST\QueryParam(name="page", requirements="\d+", nullable=true)
     * @REST\QueryParam(name="limit", requirements="\d+", default="10")
     */
    public function allAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $page  = $paramFetcher->get('page');
        $page  = null == $page ? 1 : $page;
        $limit = $paramFetcher->get('limit');

        $queryBuilder = $this
            ->get('doctrine.orm.default_entity_manager')
            ->createQueryBuilder('u')
            ->select('u')
            ->from('Acme\ApiBundle\Entity\User', 'u')
            ->orderBy('u.createdAt', 'DESC');

        $pager = new Pagerfanta(new DoctrineORMAdapter($queryBuilder));
        $pager->setMaxPerPage($limit);
        $pager->setCurrentPage($page);

        if ('html' === $request->getRequestFormat()) {
            return [ 'pager' => $pager ];
        }

        return new UserCollection($pager->getCurrentPageResults());
    }

    /**
     * @REST\Get("/users/{id}.{_format}", requirements={"id"="\d+"}, defaults={"_format"="html"})
     * @REST\View()
     */
    public function getAction(User $user)
    {
        return $user;
    }

    /**
     * @REST\Post("/users.{_format}", defaults={"_format"="json"})
     * @REST\View()
     */
    public function postAction(Request $request)
    {
        return $this->processForm($request, new User());
    }

    /**
     * @REST\Put("/users/{id}.{_format}", defaults={"_format"="json"})
     * @REST\View()
     */
    public function putAction(Request $request, User $user)
    {
        return $this->processForm($request, $user);
    }

    private function processForm(Request $request, User $user)
    {
        $update = $request->isMethod('PUT');
        $form   = $this->createForm(new UserType(), $user, [
            'method' => $update ? 'PUT' : 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEm()->persist($user);
            $this->getEm()->flush();

            return $this->routeRedirectView(
                'acme_api_user_get',
                [ 'id' => $user->getId() ],
                $update ? '204' : '201'
            );
        }

        return $this->view($form, 400);
    }

    private function getEm()
    {
        return $this->get('doctrine.orm.default_entity_manager');
    }
}
