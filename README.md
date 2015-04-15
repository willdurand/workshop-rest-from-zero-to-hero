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

    $ cp .env.dist .env

Run the application:

    $ bin/console server:start --router=`pwd`/router.php

**Note 1:** defining the `--router` option is required here because default
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

    $ bin/behat features/basic-serialization.feature

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

## The `ApiBundle` (a.k.a. the Read part)

**->** Create a bundle called `ApiBundle`.

**->** Create a `User` class with a few attributes (`id`, `firstName`, `lastName`,
`birthDate`, etc.) and configure Doctrine mapping on it.

**->** Uncomment the `DoctrineFixturesBundle` and `HautelookAliceBundle` into the
`app/AppKernel.php` file. You can now quickly write _fixtures_ using
[Alice](https://github.com/nelmio/alice/blob/master/README.md).

**->** Create a `Acme\ApiBundle\DataFixtures\ORM\Loader`. This class should extend
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

**->** Write Alice configuration in a `users.yml` file, and run the command above to
load fixtures:

    $ bin/console doctrine:fixtures:load

**->** Add a `UserController` to the `ApiBundle`.

**->** Write a method named `allAction()` that returns all users throught a FOS
view, using annotations (`@Get` and `@View`).


## Pagination

[Pagerfanta](https://github.com/whiteoctober/Pagerfanta) is a well-known and
powerful PHP pager.

**->** In order to use it, uncomment the line to enable the
[WhiteOctoberPagerfantaBundle](https://github.com/whiteoctober/WhiteOctoberPagerfantaBundle)
in the `AppKernel` class.

**->** By combining FOSRestBundle `@QueryParam` and the Pagerfanta, modify the
`allAction()` to provide a paginated collection.

**->** The JSON response should look like this:

```json
{
    "users": [
        {
            "birth_date": "2012-03-24T00:00:00+0100",
            "first_name": "Adah",
            "id": 1,
            "last_name": "Reichel"
        }
    ]
}
```

**->** The XML response should look like this:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<users>
    <user id="1">
        <first_name><![CDATA[Adah]]></first_name>
        <last_name><![CDATA[Reichel]]></last_name>
        <birth_date><![CDATA[2012-03-24T00:00:00+0100]]></birth_date>
    </user>
</users>
```

**->** Create a `getAction()` that returns a given user.


## Tests

**->** Write scenarios (tests) to cover the features of the `allAction()`
method. Behat runs the application with the `test` environment. Be sure to
create a database and load fixtures in this environment.


## Create

**->** Use the [Form](http://symfony.com/doc/current/book/forms.html) component
to add a new user. This action must be named `postAction()`.

You will have to configure the
[Validation](http://symfony.com/doc/current/book/validation.html) layer.

In a better world, you would not use
[Behat/Symfony2Extension](https://github.com/Behat/Symfony2Extension) but rather
the [Behat/WebApiExtension](https://github.com/Behat/WebApiExtension). Because
we are not in the real world, let's continue with the former extension. You
can find two _scenarios_ below:

```
    Scenario: Add a new user
        Given I am on "/api/users"
        When I send:
        """
        {
            "user": {
                "firstName": "John",
                "lastName": "Doe",
                "birthDate": "1988-01-01"
            }
        }
        """
        Then the status code should be 201

    Scenario: Add a new user with invalid data
        Given I am on "/api/users"
        When I send:
        """
        {
            "user": {
            }
        }
        """
        Then the status code should be 400
        And it should contain the following JSON content:
        """
        {"code":400,"message":"Validation Failed","errors":{"children":{"firstName":{"errors":["This value should not be blank."]},"lastName":{"errors":["This value should not be blank."]},"birthDate":[]}}}
        """
```


## Update

**->** Refactor your code to allow modifying existing entities.

**->** Write a scenario to cover this new feature.


## Content Negotiation

The FOSRestBundle provides a [Format
Listener](http://symfony.com/doc/master/bundles/FOSRestBundle/3-listener-support.html#format-listener)
that does content negotitation (black) magic for you, leveraging the
[Negotiation](https://github.com/willdurand/Negotiation) library.

**->** Enable the format listener, and play with `curl` or
[HTTPie](https://github.com/jkbr/httpie).


## Documentation

Introducing the
[NelmioApiDocBundle](https://github.com/nelmio/NelmioApiDocBundle), a bundle
that generates great documentation for you!

**->** Write documentation for the different actions. Group them into a _users_
section.
