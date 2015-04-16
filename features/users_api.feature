Feature: Users API
    In order to create awesome stuff
    As an API client
    I need to be able to interact with an HTTP API

#    Scenario: Get all users
#        Given I am on "/api/users"
#        When I reload the page
#        Then it should be a "html" content
#        And I should see a ".users" element

#    Scenario: Get all users
#        Given I am on "/api/users.json"
#        When I reload the page
#        Then it should be a "json" content

    Scenario: Add a new user
        Given I am on "/api/users"
        When I send the following JSON document:
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
        When I send the following JSON document:
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

    Scenario: Add a new user with XML
        Given I am on "/api/users"
        When I send the following XML document:
        """
        <xml>
            <user>
                <firstName>Pim</firstName>
                <lastName>Pam</lastName>
                <birthDate>1988-01-01</birthDate>
            </user>
        </xml>
        """
        Then the status code should be 201


    Scenario: Update an existing user
        Given I am on "/api/users/1"
        When I send the following JSON document to "/api/users/1":
        """
        {
            "user": {
                "firstName": "Babar",
                "lastName": "The Elephant",
                "birthDate": "1988-01-01"
            }
        }
        """
        Then the status code should be 204

    Scenario: Paginated collection with hypermedia links
        Given I am on "/api/users"
        When I ask for "application/json" content
        Then I should get a "users" embedded resource
        And a "first" link should exist
        And a "last" link should exist
        And a "self" link should exist
        And a "next" link should exist
        And a "previous" link should not exist
        And the "page" attribute should be equal to "1"

    Scenario: Paginated collection with hypermedia links
        Given I am on "/api/users"
        And I ask for "application/json" content
        When I follow the "next" link
        Then I should get a "users" embedded resource
        And a "previous" link should exist
        And the "page" attribute should be equal to "2"

    Scenario: Paginated collection with hypermedia links
        Given I am on "/api/users"
        And I ask for "application/json" content
        When I follow the "last" link
        Then I should get a "users" embedded resource
        And a "next" link should not exist

    Scenario: Follow links to retrieve a user
        Given I am on "/api/users"
        And I ask for "application/json" content
        When I follow the "self" link of the user "1"
        Then I should get a user
