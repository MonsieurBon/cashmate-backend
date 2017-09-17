<?php

namespace Tests\AppBundle\Controller;

class ApiControllerTest extends GraphQLApiTestCase
{
    public function testIndexDefault()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api');
        $response = $client->getResponse();
        $content = $response->getContent();
        $json = json_decode($content);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Your GraphQL endpoint is ready! Use GraphiQL to browse API.", $json->data->hello);
    }

    public function testIndexHello()
    {
        $client = $this->sendApiQuery('{"query":"query {hello}","variables":null}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $json = json_decode($content);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Your GraphQL endpoint is ready! Use GraphiQL to browse API.", $json->data->hello);
    }

    public function testIndexUnknownQueryThrowsException() {
        $client = $this->sendApiQuery('{"query":"query {foobar}","variables":null}');
        $respose = $client->getResponse();

        $this->assertEquals(400, $respose->getStatusCode());
    }

    public function testIndexAccounts() {
        $client = $this->sendApiQuery('{"query":"query {accounts {name}}","variables":null}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $json = json_decode($content);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, count($json->data->accounts));
        $this->assertEquals('Haushalt', $json->data->accounts[0]->name);
        $this->assertEquals('Sparen', $json->data->accounts[1]->name);
    }

    public function testRedirect()
    {
        $redirectedMethods = ['GET', 'HEAD', 'PUT', 'DELETE'];

        $client = static::createClient();

        foreach ($redirectedMethods as $method) {
            $client->request($method, '/api');
            $this->assertEquals(301, $client->getResponse()->getStatusCode());
        }
    }
}
