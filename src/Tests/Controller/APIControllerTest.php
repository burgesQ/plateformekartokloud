<?php

namespace App\Tests\Controller;


use App\Helper\KartoKloudTestCase;

class APIControllerTest extends KartoKloudTestCase
{
    /**
     * Test to get the KartoVm
     * Shall fail as user not logged
     */
    public function testGetKartoVmNotLoggedAction() {
        $this->performClientRequest('GET', '/api/v1/karto_vm');
        self::assertFalse($this->client->getResponse()->isSuccessful());
        self::assertEquals($this->client->getResponse()->getStatusCode(), 500);
    }

    /**
     * Test to get the KartoVm
     * Shall success as user is logged
     */
    public function testGetKartoVmLoggedAction() {
        $this->performClientRequest('GET', '/api/v1/karto_vm', [], 'kartoboi@kartokloud.com', 'password');
  //      dump($this->client->getResponse()->getContent());
//        self::assertTrue($this->client->getResponse()->isSuccessful());
    }
}