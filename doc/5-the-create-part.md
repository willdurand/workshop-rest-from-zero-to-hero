# 5 - The "Create" Part

## 5.1 - Form

**Task:** Use the [Form](http://symfony.com/doc/current/book/forms.html) component
to add a new user. This action must be named `postAction()`.

You will have to configure the
[Validation](http://symfony.com/doc/current/book/validation.html) layer.

## 5.2 - Testing, again!

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
        {
            "code": 400,
            "errors": {
                "children": {
                    "birthDate": [],
                    "firstName": {
                        "errors": [
                            "This value should not be blank."
                        ]
                    },
                    "lastName": {
                        "errors": [
                            "This value should not be blank."
                        ]
                    }
                }
            },
            "message": "Validation Failed"
        }
        """
```

---

[Previous](4-testing.md)&nbsp;|&nbsp;[Back to the
index](https://github.com/willdurand/workshop-rest-from-zero-to-hero#instructions)
&nbsp;|&nbsp;[Next](6-the-update-part.md)
