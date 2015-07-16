<?php

class NoIpAccountTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorSet()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'testHostname';

        $model = new \noip\Models\NoIpAccount($username, $password, $hostName);
        $this->assertEquals($username, $model->getUsername());
        $this->assertEquals($password, $model->getPassword());
        $this->assertEquals($hostName, $model->getHostName());
    }

    public function testConstructorNoParams()
    {
        $model = new \noip\Models\NoIpAccount();
        $this->assertEmpty($model->getHostName());
        $this->assertEmpty($model->getPassword());
        $this->assertEmpty($model->getUsername());
    }

    public function testSettersAndGetters()
    {
        $username = 'testUsername';
        $password = 'testPassword';
        $hostName = 'testHostname';

        $model = new \noip\Models\NoIpAccount();
        $model->setHostName($hostName);
        $this->assertEquals($hostName, $model->getHostName());

        $model->setPassword($password);
        $this->assertEquals($password, $model->getPassword());

        $model->setUsername($username);
        $this->assertEquals($username, $model->getUsername());
    }
}
 