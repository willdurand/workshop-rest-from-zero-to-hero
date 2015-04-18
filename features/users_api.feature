Feature: Users API
    In order to create awesome stuff
    As an API client
    I need to be able to interact with an HTTP API

    Scenario: Get all users
        Given I am on "/api/users"
        When I reload the page
        Then it should be a "html" content
        And I should see a ".users" element

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
