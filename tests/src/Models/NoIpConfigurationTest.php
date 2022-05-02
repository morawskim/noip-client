<?php

class NoIpConfigurationTest extends \PHPUnit\Framework\TestCase
{
    public function testGettersAndSetters()
    {
        $hostname = 'hostname';
        $password = 'password';
        $username = 'username';

        $config = new \noip\Models\NoIpConfiguration();
        $config->setHostname($hostname);
        $config->setPassword($password);
        $config->setUsername($username);

        $this->assertEquals($hostname, $config->getHostname());
        $this->assertEquals($password, $config->getPassword());
        $this->assertEquals($username, $config->getUsername());
    }

    public function testParseArray()
    {
        $hostname = 'hostname';
        $password = 'password';
        $username = 'username';

        $config = [
            'NOIP_USERNAME' => $username,
            'NOIP_PASSWORD' => $password,
            'NOIP_HOSTNAME' => $hostname,
        ];
        $config = \noip\Models\NoIpConfiguration::parseArray($config);
        $this->assertEquals($hostname, $config->getHostname());
        $this->assertEquals($username, $config->getUsername());
        $this->assertEquals($password, $config->getPassword());
    }

    public function testParseArrayNoData()
    {
        $config = [];
        $config = \noip\Models\NoIpConfiguration::parseArray($config);
        $this->assertNull($config->getHostname());
        $this->assertNull($config->getUsername());
        $this->assertNull($config->getPassword());
    }
}
