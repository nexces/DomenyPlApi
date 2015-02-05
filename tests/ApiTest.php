<?php

namespace DomenyPl\Test;

use DomenyPl\Api;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    const API_USER = 'example';
    const API_PASSWORD = 'example';

    public function testApiLoads()
    {
        $api = new Api(self::API_USER, self::API_PASSWORD);
        $this->assertInstanceOf('DomenyPl\Api', $api);
        $this->assertEquals(self::API_USER, $api->getApiLogin());
        $this->assertEquals(self::API_PASSWORD, $api->getApiPassword());
        $this->assertEquals('https://api.domeny.pl/', $api->getApiUrl());
    }

    public function testApiReceivesResponse()
    {
        $api = new Api(self::API_USER, self::API_PASSWORD);
        $result = $api->sendCommand('account_info');
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('code_description', $result);
        $this->assertArrayHasKey('execution_time', $result);
        $this->assertArrayHasKey('command_name', $result);
        $this->assertArrayHasKey('uid', $result);
        $this->assertArrayHasKey('result', $result);
        $this->assertInternalType('array', $result['result']);
        $this->assertEquals('account_info', $result['command_name']);
    }
}
