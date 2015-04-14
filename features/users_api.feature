Feature: Users API
    In order to create awesome stuff
    As an API client
    I need to be able to interact with an HTTP API

    Scenario: Get all users
        Given I am on "/api/users"
        When I reload the page
        Then it should be a "html" content
        And I should see a ".users" element

    Scenario: Get all users
        Given I am on "/api/users.json"
        When I reload the page
        Then it should be a "json" content
