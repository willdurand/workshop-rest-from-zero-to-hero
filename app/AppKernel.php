<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),

            // 1. FOSRestBundle (+ JMSSerializerBundle)
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            // Useful to directly return "View" objects
            // cf. http://symfony.com/doc/master/bundles/FOSRestBundle/3-listener-support.html#view-response-listener
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            // 2. Fixtures
            // new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            // new Hautelook\AliceBundle\HautelookAliceBundle(),

            // 3. Pagination
            // new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),

            // 4. NelmioApiDocBundle
            // new Nelmio\ApiDocBundle\NelmioApiDocBundle(),

            new Acme\HelloBundle\AcmeHelloBundle()
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config.yml');

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $loader->load(function ($container) {
                $container->loadFromExtension('web_profiler', array(
                    'toolbar' => true,
                ));
            });
        }

        $app = $this;
        $loader->load(function ($container) use ($app) {
            $container->setParameter('enable_test', 'test' === $app->getEnvironment());
        });
    }
}
