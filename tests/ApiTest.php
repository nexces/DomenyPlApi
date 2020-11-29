<?php

namespace DomenyPl\Test;

use DomenyPl\Api;
use Exception;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
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
        $this->assertIsArray($result);
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('code_description', $result);
        $this->assertArrayHasKey('execution_time', $result);
        $this->assertArrayHasKey('command_name', $result);
        $this->assertArrayHasKey('uid', $result);
        $this->assertArrayHasKey('result', $result);
        $this->assertIsArray($result['result']);
        $this->assertEquals('account_info', $result['command_name']);
    }

    public function testApiTimeout()
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        for ($port = 50000; $port <= 51000; $port++) {
            $result = socket_bind($socket, '127.0.0.1', $port);
            if ($result) {
                break;
            }
        }
        if (!$result) {
            $this->markTestSkipped('Could not allocate local port for timeout test');
        }

        $this->expectException(Exception::class);
        $this->expectExceptionCode(28);

        $api = new Api(self::API_USER, self::API_PASSWORD, 'http://127.0.0.1:' . $port);
        $api->setTimeout(1);
        $api->sendCommand('bla');
    }
}
