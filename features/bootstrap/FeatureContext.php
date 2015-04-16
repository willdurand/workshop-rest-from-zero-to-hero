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

    private $previousAcceptHeader;

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

    /**
     * @When I send the following JSON document to :arg1:
     */
    public function iSendTheFollowingJsonDocumentTo($arg1, PyStringNode $string)
    {
        $this->request('PUT', $arg1, $string, [ 'Content-Type' => 'application/json' ]);
    }

    /**
     * @When I ask for :accept content
     */
    public function iAskForContent($accept)
    {
        $this->request('GET', $this->getSession()->getCurrentUrl(), null, [ 'Accept' => $accept ]);
        $this->previousAcceptHeader = $accept;
    }

    /**
     * @Then I should get a :embeddedRel embedded resource
     */
    public function iShouldGetAEmbeddedResource($embeddedRel)
    {
        $this->ensureAcceptJson();

        $json = json_decode($this->response->getBody(), true);

        assertArrayHasKey('_embedded', $json);
        assertArrayHasKey($embeddedRel, $json['_embedded']);
    }

    /**
     * @Then a :linkName link should exist
     */
    public function aLinkShouldExist($linkName)
    {
        $this->ensureAcceptJson();

        $json = json_decode($this->response->getBody(), true);

        assertArrayHasKey('_links', $json);
        assertArrayHasKey($linkName, $json['_links']);
    }

    /**
     * @Then a :linkName link should not exist
     */
    public function aLinkShouldNotExist($linkName)
    {
        $this->ensureAcceptJson();

        $json = json_decode($this->response->getBody(), true);

        assertArrayHasKey('_links', $json);
        assertArrayNotHasKey($linkName, $json['_links']);
    }

    /**
     * @When I follow the :linkName link
     */
    public function iFollowTheLink($linkName)
    {
        $this->ensureAcceptJson();

        $json = json_decode($this->response->getBody(), true);

        $this->aLinkShouldExist($linkName);

        $this->request('GET', $json['_links'][$linkName]['href'], null, [ 'Accept' => $this->previousAcceptHeader ]);
    }

    /**
     * @Then the :name attribute should be equal to :expectedValue
     */
    public function theAttributeShouldBeEqualTo($name, $expectedValue)
    {
        $this->ensureAcceptJson();

        $json = json_decode($this->response->getBody(), true);

        assertArrayHasKey($name, $json);
        assertEquals($expectedValue, $json[$name]);
    }

    /**
     * @When I follow the :arg1 link of the user :userIndex
     */
    public function iFollowTheLinkOfTheUser($linkName, $userIndex)
    {
        $this->ensureAcceptJson();

        $json = json_decode($this->response->getBody(), true);

        $this->iShouldGetAEmbeddedResource('users');
        $users = $json['_embedded']['users'];

        assertTrue(isset($users[$userIndex]));
        $user = $users[$userIndex];

        $this->request('GET', $user['_links'][$linkName]['href'], null, [ 'Accept' => $this->previousAcceptHeader ]);
    }

    /**
     * @Then I should get a user
     */
    public function iShouldGetAUser()
    {
        $this->ensureAcceptJson();

        $json = json_decode($this->response->getBody(), true);

        assertArrayHasKey('id', $json);
        assertArrayHasKey('last_name', $json);
        assertArrayHasKey('first_name', $json);
        assertArrayHasKey('birth_date', $json);
    }

    private function request($method, $uri, $string, array $headers)
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

    private function ensureAcceptJson()
    {
        if (null === $this->previousAcceptHeader) {
            throw new \Exception('You must ask for a specific content type (Accept header) first');
        }

        if ('application/json' !== $this->previousAcceptHeader) {
            throw new PendingException();
        }
    }
}
