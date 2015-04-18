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

class UserController extends FOSRestController
{
    /**
     * @REST\Get("/users.{_format}", defaults={"_format"="html"}),
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
}
