<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use GuzzleHttp\Client;

require_once __DIR__ . '/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    private $client;

    private $response;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct($baseUrl)
    {
        $this->client = new Client([ 'base_url' => $baseUrl ]);
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

    /**
     * @Then the status code should be :statusCode
     */
    public function theStatusCodeShouldBe($statusCode)
    {
        assertEquals($statusCode, $this->response->getStatusCode());
    }

    /**
     * @Then it should contain the following JSON content:
     */
    public function itShouldContainTheFollowingJsonContent(PyStringNode $string)
    {
        assertEquals($string, (string) $this->response->getBody());
    }

    /**
     * @When I send the following JSON document:
     */
    public function iSendTheFollowingJsonDocument(PyStringNode $string)
    {
        $this->request($string, [ 'Content-Type' => 'application/json' ]);
    }

    /**
     * @When I send the following XML document:
     */
    public function iSendTheFollowingXmlDocument(PyStringNode $string)
    {
        $this->request($string, [ 'Content-Type' => 'application/xml' ]);
    }

    private function request($string, array $headers)
    {
        try {
            $this->response = $this->client->post(
                '/api/users',
                [
                    'headers' => $headers,
                    'body'    => $string,
                ]
            );
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->response = $e->getResponse();
        }
    }
}
