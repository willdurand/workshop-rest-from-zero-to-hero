REST: From Zero To Hero
=======================

This provided Symfony edition is based on the [Symfony minimal
edition](https://github.com/beberlei/symfony-minimal-distribution).


## Installation

Get the code by cloning this repository:

    $ git clone git@github.com:willdurand/workshop-rest-from-zero-to-hero.git

Install the project's dependencies:

    $ composer install

Configure the project by renaming the `.env.dist` file to `.env`:

    cp .env.dist .env

Run the application:

    bin/console server:start --router=`pwd`/web/index.php

**Note:** defining the `--router` option is required here because default
Symfony routers hardcode the front controller name (in this project it is
`index.php`, while Symfony uses `app[_dev].php`).

**Note 2:** as you might notice, the `console` script is located into the `bin/`
folder, not in `app/`.

Browse [http://localhost:8000/](http://localhost:8000/).


## Serialization

Serialization is brought to your by the
[JMSSerializerBundle](http://jmsyst.com/bundles/JMSSerializerBundle), for free
;-)

**->** You can start by modifying the `DefaultController` class to use the
`jms_serializer` service and return either some XML, HTML, or JSON.

A small [Behat](http://docs.behat.org) test suite is provided:

    bin/behat features/basic-serialization.feature

The [FOSRestBundle](https://github.com/FriendsOfSymfony/FOSRestBundle)
integrates with the JMSSerializerBundle, and provides the same feature throught
the concept of
"[views](http://symfony.com/doc/master/bundles/FOSRestBundle/2-the-view-layer.html)".

**->** Configure the FOSRestBundle to leverage [the View
layer](http://symfony.com/doc/master/bundles/FOSRestBundle/2-the-view-layer.html).

**->** Configure the XML output to get the following document:

```xml
<hello>
    <name><![CDATA[ will ]]></name>
</hello>
```

## The `ApiBundle`

**->** Create a bundle called `ApiBundle`

**->** Create a `User` class with a few attributes (`id`, `firstName`, `lastName`,
`birthDate`, etc.) and configure Doctrine mapping on it.

**->** Uncomment the `DoctrineFixturesBundle` and `HautelookAliceBundle` into the
`app/AppKernel.php` file. You can now quickly write _fixtures_ using
[Alice](https://github.com/nelmio/alice/blob/master/README.md).

Create a `Acme\ApiBundle\DataFixtures\ORM\Loader`. This class should extend
`Hautelook\AliceBundle\Alice\DataFixtureLoader`, and implement a `getFixtures()`
method:

```php
protected function getFixtures()
{
    return [
        __DIR__ . '/users.yml',
    ];
}
```

Write Alice configuration in a `users.yml` file, and run the command above to
load fixtures:

    bin/console doctrine:fixtures:load

**->** Add a `UserController` to the `ApiBundle`

**->** Write a `allAction()` method that returns all users throught a FOS view,
using annotations (`@Get` and `@View`)
