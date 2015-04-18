<?php

namespace Acme\ApiBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class Loader extends DataFixtureLoader
{
    /**
     * {@inheritDoc}
     */
    protected function getFixtures()
    {
        return [
            __DIR__ . '/users.yml',
        ];
    }
}
