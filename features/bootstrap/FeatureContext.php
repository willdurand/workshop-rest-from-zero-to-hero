<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

require_once __DIR__ . '/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Then it should be a :format content
     */
    public function itShouldBeAnContent($format)
    {
        $headers = $this->getSession()->getResponseHeaders();

        assertContains($format, current($headers['content-type']));
    }

    /**
     * @Then it should contain a :name key whose value is :value
     */
    public function itShouldContainAKeyWhoseValueIs($name, $value)
    {
        $json = json_decode($this->getSession()->getPage()->getContent(), true);

        assertArrayHasKey($name, $json);
        assertEquals($value, $json[$name]);
    }

    /**
     * @Then it should contain a :element element whose value is :value
     */
    public function itShouldContainAElementWhoseValueIs($element, $value)
    {
        $xml = simplexml_load_string($this->getSession()->getPage()->getContent());

        assertTrue(isset($xml->{$element}));
        assertEquals($value, $xml->{$element});
    }
}
