<?php

class NoIpCommandTest extends PHPUnit_Framework_TestCase
{
    public function testTheSameIpWithoutForceArgument()
    {
        $model = new \noip\Models\NoIpAccount(
            'test',
            'password',
            'hostname.example.com'
        );

        $apiMock = $this->createMock(\noip\Models\NoIpApi::class);
        $apiMock->method('getMyIp')->willReturn('8.8.8.8');
        $apiMock->method('getCurrentAssignedIp')->willReturn('8.8.8.8');

        $command = new \noip\Commands\NoIpCommand('noip');
        $command->setModel($model);
        $command->setApiModel($apiMock);

        $tester = new \Symfony\Component\Console\Tester\CommandTester($command);
        $tester->execute(['--file' => '/dev/null'], []);
        $this->assertSame(0, $tester->getStatusCode());
        rewind($tester->getOutput()->getStream());
        $this->assertContains('The same IP', stream_get_contents($tester->getOutput()->getStream()));
    }

    public function testTheSameIpWithForceArgument()
    {
        $model = new \noip\Models\NoIpAccount(
            'test',
            'password',
            'hostname.example.com'
        );

        $apiMock = $this->createMock(\noip\Models\NoIpApi::class);
        $apiMock->method('getMyIp')->willReturn('8.8.8.8');
        $apiMock->method('getCurrentAssignedIp')->willReturn('8.8.8.8');
        $apiMock->method('update')->with('8.8.8.8')->willReturn(true);

        $command = new \noip\Commands\NoIpCommand('noip');
        $command->setModel($model);
        $command->setApiModel($apiMock);

        $tester = new \Symfony\Component\Console\Tester\CommandTester($command);
        $tester->execute(['--file' => '/dev/null', 'force' => 'force'], []);
        $this->assertSame(0, $tester->getStatusCode());
        rewind($tester->getOutput()->getStream());
        $contents = stream_get_contents($tester->getOutput()->getStream());
        $this->assertContains('Updating IP address from', $contents);
        $this->assertContains('SUCCESS', $contents);
    }

    public function testDifferentIp()
    {
        $model = new \noip\Models\NoIpAccount(
            'test',
            'password',
            'hostname.example.com'
        );

        $apiMock = $this->createMock(\noip\Models\NoIpApi::class);
        $apiMock->method('getMyIp')->willReturn('8.8.8.8');
        $apiMock->method('getCurrentAssignedIp')->willReturn('1.1.1.1');
        $apiMock->method('update')->with('8.8.8.8')->willReturn(true);

        $command = new \noip\Commands\NoIpCommand('noip');
        $command->setModel($model);
        $command->setApiModel($apiMock);

        $tester = new \Symfony\Component\Console\Tester\CommandTester($command);
        $tester->execute(['--file' => '/dev/null', 'force' => 'force'], []);
        $this->assertSame(0, $tester->getStatusCode());
        rewind($tester->getOutput()->getStream());
        $contents = stream_get_contents($tester->getOutput()->getStream());
        $this->assertContains('Updating IP address from', $contents);
        $this->assertContains('SUCCESS', $contents);
    }

    public function testDifferentIpError()
    {
        $model = new \noip\Models\NoIpAccount(
            'test',
            'password',
            'hostname.example.com'
        );

        $apiMock = $this->createMock(\noip\Models\NoIpApi::class);
        $apiMock->method('getMyIp')->willReturn('8.8.8.8');
        $apiMock->method('getCurrentAssignedIp')->willReturn('1.1.1.1');
        $apiMock->method('update')
            ->with('8.8.8.8')
            ->will($this->throwException(new \noip\Exception\NoIpApiException('qwe')));

        $command = new \noip\Commands\NoIpCommand('noip');
        $command->setModel($model);
        $command->setApiModel($apiMock);

        $tester = new \Symfony\Component\Console\Tester\CommandTester($command);
        $tester->execute(['--file' => '/dev/null'], []);
        $this->assertSame(1, $tester->getStatusCode());
        rewind($tester->getOutput()->getStream());
        $this->assertNotContains('SUCCESS', stream_get_contents($tester->getOutput()->getStream()));
    }
}
