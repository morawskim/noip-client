<?php

class NoIpApiTest extends \PHPUnit_Framework_TestCase
{
    public function testAssignedIpFakeDomain()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model);

//        $api->getCurrentAssignedIp();
    }

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

    /**
     * @expectedException RuntimeException
     */
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

        $client = new \Guzzle\Http\Client();
        $client->addSubscriber(new Guzzle\Plugin\Mock\MockPlugin(array(
            new \Guzzle\Http\Message\Response(200, null, 'nohost')
        )));

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);
        $this->setExpectedException('\noip\Exception\NoIpApiException', null, \noip\Exception\NoIpApiException::NOHOST);

        $api->update($newIp);
    }

    public function testApiFailError()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';
        $newIp = '8.8.8.8';

        $client = new \Guzzle\Http\Client();
        $client->addSubscriber(new Guzzle\Plugin\Mock\MockPlugin(array(
            new \Guzzle\Http\Message\Response(200, null, '911')
        )));

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);
        $this->setExpectedException('\noip\Exception\NoIpApiException', null, \noip\Exception\NoIpApiException::FATAL);

        $api->update($newIp);
    }

    public function testApiAbuseError()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';
        $newIp = '8.8.8.8';

        $client = new \Guzzle\Http\Client();
        $client->addSubscriber(new Guzzle\Plugin\Mock\MockPlugin(array(
            new \Guzzle\Http\Message\Response(200, null, 'abuse')
        )));

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);
        $this->setExpectedException('\noip\Exception\NoIpApiException', null, \noip\Exception\NoIpApiException::ABUSE);

        $api->update($newIp);
    }

    public function testApiBadAgentError()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';
        $newIp = '8.8.8.8';

        $client = new \Guzzle\Http\Client();
        $client->addSubscriber(new Guzzle\Plugin\Mock\MockPlugin(array(
            new \Guzzle\Http\Message\Response(200, null, 'badagent'),
        )));

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);
        $this->setExpectedException('\noip\Exception\NoIpApiException', null, \noip\Exception\NoIpApiException::BADAGENT);

        $api->update($newIp);
    }

    public function testApiBadAuthError()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';
        $newIp = '8.8.8.8';

        $client = new \Guzzle\Http\Client();
        $client->addSubscriber(new Guzzle\Plugin\Mock\MockPlugin(array(
            new \Guzzle\Http\Message\Response(200, null, 'badauth')
        )));

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);
        $this->setExpectedException('\noip\Exception\NoIpApiException', null, \noip\Exception\NoIpApiException::BADAUTH);

        $api->update($newIp);
    }

    public function testApiDonatorError()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';
        $newIp = '8.8.8.8';

        $client = new \Guzzle\Http\Client();
        $client->addSubscriber(new Guzzle\Plugin\Mock\MockPlugin(array(
            new \Guzzle\Http\Message\Response(200, null, '!donator')
        )));

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);
        $this->setExpectedException('\noip\Exception\NoIpApiException', null, \noip\Exception\NoIpApiException::DONATOR);

        $api->update($newIp);
    }

    public function testApiUnknownError()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'fakedomain.example';
        $newIp = '8.8.8.8';

        $client = new \Guzzle\Http\Client();
        $client->addSubscriber(new Guzzle\Plugin\Mock\MockPlugin(array(
            new \Guzzle\Http\Message\Response(200, null, 'unknown')
        )));

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $api = new \noip\Models\NoIpApi($model, $client);
        $this->setExpectedException('\noip\Exception\NoIpApiException', null, \noip\Exception\NoIpApiException::UNKNOWN);

        $api->update($newIp);
    }
}
