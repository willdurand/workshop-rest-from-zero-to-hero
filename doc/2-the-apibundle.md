# 2 - The `ApiBundle` (a.k.a. the Read part)

## 2.1 - Bootstrap

**Task:** Create a bundle called `ApiBundle`.

**Task:** Create a `User` class with a few attributes (`id`, `firstName`, `lastName`,
`birthDate`, etc.) and configure Doctrine mapping on it.

## 2.2 - Fixtures with Alice

**Task:** Uncomment the `DoctrineFixturesBundle` and `HautelookAliceBundle` into the
`app/AppKernel.php` file. You can now quickly write _fixtures_ using
[Alice](https://github.com/nelmio/alice/blob/master/README.md).

**Task:** Create a `Acme\ApiBundle\DataFixtures\ORM\Loader`. This class should extend `Hautelook\AliceBundle\Alice\DataFixtureLoader` and implement a `getFixtures()` method:

```php
protected function getFixtures()
{
    return [
        __DIR__ . '/users.yml',
    ];
}
```

**Task:** Write Alice configuration in a `users.yml` file, and run the command above to load fixtures:

    $ bin/console doctrine:fixtures:load

> **Tip:** Always develop your application with (fake) data. Fixtures are a convenient way to populate your database without any effort.

## 2.3 - Playing with FOSRestBundle Views

**Task:** Add an empty controller `UserController` into your `ApiBundle`.

**Task:** Write a method named `allAction()` that returns all users through a FOS
view, using annotations (`@Get` and `@View`).

The JSON response should look like this:

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

The XML response should look like this:

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

---

[Previous](1-serialization.md)&nbsp;|&nbsp;[Back to the
index](https://github.com/willdurand/workshop-rest-from-zero-to-hero#instructions)
&nbsp;|&nbsp;[Next](3-pagination.md)
