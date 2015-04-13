<?php

namespace Acme\HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function helloAction(Request $request, $name)
    {
        $data   = [ 'name' => $name ];
        $format = $request->getRequestFormat();

        if ('html' !== $format) {
            $serializedData = $this
                ->get('jms_serializer')
                ->serialize($data, $format);

            return new Response($serializedData, 200, [
                // WARNING: Don't do this at home!
                'Content-Type' => 'application/' . $format,
            ]);
        }

        return $this->render(
            'AcmeHelloBundle:Default:hello.html.twig',
            $data
        );
    }
}
