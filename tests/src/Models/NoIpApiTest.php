<?php

use noip\Exception\NoIpApiException;

class NoIpApiTest extends \PHPUnit\Framework\TestCase
{
    public function testMyIp()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';
        $fakeIp = '8.8.8.8';

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);

        $api = $this->getMockBuilder(\noip\Models\NoIpApi::class)
                ->setConstructorArgs([$model])
                ->getMock();
        $api->expects($this->once())->method('getMyIp')->will($this->returnValue($fakeIp));

        $return = $api->getMyIp();
        $this->assertEquals($fakeIp, $return);
    }

    public function testCurrentIpNotFund()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = $this->getMockBuilder(\noip\Models\NoIpApi::class)
            ->setConstructorArgs([$model])
            ->getMock();
        $api->expects($this->once())->method('getMyIp')->will($this->throwException(new RuntimeException()));

        $this->expectException(RuntimeException::class);
        $api->getMyIp();
    }

    /**
     */
    public function testApiNoHostError()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';
        $newIp = '8.8.8.8';

        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(200, [], 'nohost'),
        ]);
        $handlerStack = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handlerStack]);

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);
        $this->expectException(NoIpApiException::class);
        $this->expectExceptionCode(\noip\Exception\NoIpApiException::NOHOST);

        $api->update($newIp);
    }

    public function testApiFailError()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';
        $newIp = '8.8.8.8';

        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(200, [], '911'),
        ]);
        $handlerStack = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handlerStack]);

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);
        $this->expectException(NoIpApiException::class);
        $this->expectExceptionCode(\noip\Exception\NoIpApiException::FATAL);

        $api->update($newIp);
    }

    public function testApiAbuseError()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';
        $newIp = '8.8.8.8';

        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(200, [], 'abuse'),
        ]);
        $handlerStack = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handlerStack]);

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);
        $this->expectException(NoIpApiException::class);
        $this->expectExceptionCode(\noip\Exception\NoIpApiException::ABUSE);

        $api->update($newIp);
    }

    public function testApiBadAgentError()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';
        $newIp = '8.8.8.8';

        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(200, [], 'badagent'),
        ]);
        $handlerStack = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handlerStack]);

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);
        $this->expectException(NoIpApiException::class);
        $this->expectExceptionCode(\noip\Exception\NoIpApiException::BADAGENT);

        $api->update($newIp);
    }

    public function testApiBadAuthError()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';
        $newIp = '8.8.8.8';

        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(200, [], 'badauth'),
        ]);
        $handlerStack = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handlerStack]);

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);
        $this->expectException(NoIpApiException::class);
        $this->expectExceptionCode(\noip\Exception\NoIpApiException::BADAUTH);

        $api->update($newIp);
    }

    public function testApiDonatorError()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';
        $newIp = '8.8.8.8';

        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(200, [], '!donator'),
        ]);
        $handlerStack = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handlerStack]);

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);
        $this->expectException(NoIpApiException::class);
        $this->expectExceptionCode(\noip\Exception\NoIpApiException::DONATOR);

        $api->update($newIp);
    }

    public function testApiUnknownError()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';
        $newIp = '8.8.8.8';

        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(200, [], 'unknown'),
        ]);
        $handlerStack = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handlerStack]);

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);
        $this->expectException(NoIpApiException::class);
        $this->expectExceptionCode(\noip\Exception\NoIpApiException::UNKNOWN);

        $api->update($newIp);
    }

    public function testMockGetIp()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';
        $expectedId = '8.8.8.8';

        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(200, [], $expectedId),
        ]);
        $handlerStack = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handlerStack]);

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);

        $ip = $api->getMyIp();
        $this->assertEquals($expectedId, $ip);
    }

    public function testMockGetIpException()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';

        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(400, [], 'Error')
        ]);
        $handlerStack = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handlerStack]);

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('/Cant get IP address/');
        $api->getMyIp();
    }
}
