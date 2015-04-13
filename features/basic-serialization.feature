Feature: Basic Serialization
    In order to learn JMSSerializerBundle
    As a developer
    I need to actually use it

    Scenario: Retrieve HTML content
        Given I am on "/hello/william"
        When I reload the page
        Then I should see "Hello William!"
        And it should be a "html" content
