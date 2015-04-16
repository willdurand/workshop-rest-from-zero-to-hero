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
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Hateoas\Configuration\Route as HateoasRoute;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Representation\CollectionRepresentation;

class UserController extends FOSRestController
{
    /**
     * @ApiDoc(
         section="users",
         statusCodes={
             200="Returned when successful"
         })
     *
     * @REST\Get("/users")
     * @REST\View()
     * @REST\QueryParam(name="page", requirements="\d+", nullable=true, description="Current page")
     * @REST\QueryParam(name="limit", requirements="\d+", default="10", description="Maximum number of users to return")
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

        $pagerfantaFactory   = new PagerfantaFactory();
        $paginatedCollection = $pagerfantaFactory->createRepresentation(
            $pager,
            new HateoasRoute('acme_api_user_all'),
            new CollectionRepresentation(
                $pager->getCurrentPageResults(),
                'users',
                'users'
            )
        );

        return $paginatedCollection;
    }

    /**
     * @ApiDoc(section="users")
     *
     * @REST\Get("/users/{id}", requirements={"id"="\d+"})
     * @REST\View()
     */
    public function getAction(User $user)
    {
        return $user;
    }

    /**
     * @ApiDoc(
         section="users",
         input="Acme\ApiBundle\Form\Type\UserType"
     * )
     *
     * @REST\Post("/users")
     * @REST\View()
     */
    public function postAction(Request $request)
    {
        return $this->processForm($request, new User());
    }

    /**
     * @ApiDoc(
         section="users",
         input="Acme\ApiBundle\Form\Type\UserType"
     * )
     *
     * @REST\Put("/users/{id}")
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
