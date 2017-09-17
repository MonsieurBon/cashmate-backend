<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 15.09.17
 * Time: 18:06
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class GraphQLApiTestCase extends WebTestCase
{
    protected function sendApiQuery($query) {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $query);
        return $client;
    }
}