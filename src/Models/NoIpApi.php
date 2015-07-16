<?php

namespace noip\Models;
use noip\Exception\NoIpApiException;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;

class NoIpApi
{
    const IP_URL = 'http://ip1.dynupdate.no-ip.com';
    const IP_URL2 = 'http://ip2.dynupdate.no-ip.com';

    const USER_AGENT = 'PHP-NoIPUpdater/0.1.0-dev marcin@morawskim.pl';
    const API_URL = 'http://dynupdate.no-ip.com';
    const API_PATH = '/nic/update';

    protected static $IP_URLS = [self::IP_URL, self::IP_URL2];

    /**
     * @var NoIpAccount
     */
    private $model;

    /**
     * @var Client
     */
    private $httpClient;

    public function __construct($model = null, Client $client = null)
    {
        if (null !== $model) {
            $this->setModel($model);
        }

        if (null !== $client) {
            $this->setHttpClient($client);
        }
    }

    /**
     *
     * @return string
     */
    public function getMyIp()
    {
        $client = $this->getHttpClient();
        $host = $this->randomIpServer();

        $client->setBaseUrl($host);
        $requestInterface = $client->get('/');
        try {
            $response = $requestInterface->send();
            $ip = $response->getBody(true);
            return $ip;
        } catch (RequestException $e) {
            throw new \RuntimeException(sprintf('Cant get IP address. Reason: %s', $e->getMessage()), $e->getCode(), $e);
        }
    }

    protected function randomIpServer()
    {
        $index = intval(round(rand(0,count(self::$IP_URLS)-1), 0));
        return self::$IP_URLS[$index];
    }

    public function getCurrentAssignedIp()
    {
        $hostName = $this->getModel()->getHostName();
        $ip = gethostbyname($hostName);

        if ($ip === $hostName) {
            throw new \RuntimeException(sprintf('Cant get IP address of domain "%s"', $hostName));
        }

        return $ip;
    }

    public function update($newIp)
    {
        $model = $this->getModel();

        $client = $this->getHttpClient();
        $client->setBaseUrl(self::API_URL);
        $client->setUserAgent(self::USER_AGENT);
        $requestInterface = $client->get(self::API_PATH, null, array(
            'query' => array('hostname' => $model->getHostName(), 'myip' => $newIp),
        ));
        $requestInterface->setAuth($model->getUsername(), $model->getPassword(), 'Basic');

        try {
            $response = $requestInterface->send();
            $body = $response->getBody();
            $status = strval($body);
            return $this->validateResponse($status);
        } catch (RequestException $e) {
            $status = $e->getResponse()->getBody(true);
            return $this->validateResponse($status);
        }
    }

    /**
     * @return NoIpAccount
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param NoIpAccount $model
     */
    public function setModel(NoIpAccount $model)
    {
        $this->model = $model;
    }

    public function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->setHttpClient(new Client());
        }

        return $this->httpClient;
    }

    public function setHttpClient(Client $client)
    {
        $this->httpClient = $client;
    }

    /**
     * @see http://www.noip.com/integrate/response
     * @param string $status
     * @throws NoIpApiException
     * @return bool true if success, throw exception on fail
     */
    private function validateResponse($status)
    {
        if (strpos($status, 'nochg') !== false || strpos($status, 'good') !== false) {
            //success response
            return true;
        }

        switch ($status) {
            case 'nohost':
                $error = 'Hostname supplied does not exist under specified account, client exit and require user to enter new login credentials before performing and additional request.';
                $errorNum = NoIpApiException::NOHOST;
                break;
            case 'badauth':
                $error = 'Invalid username password combination';
                $errorNum = NoIpApiException::BADAUTH;
                break;
            case 'badagent':
                $error = 'Client disabled. Client should exit and not perform any more updates without user intervention.';
                $errorNum = NoIpApiException::BADAGENT;
                break;
            case '!donator':
                $error = 'An update request was sent including a feature that is not available to that particular user such as offline options.';
                $errorNum = NoIpApiException::DONATOR;
                break;
            case 'abuse':
                $error = 'Username is blocked due to abuse. Either for not following our update specifications or disabled due to violation of the No-IP terms of service. Our terms of service can be viewed here. Client should stop sending updates.';
                $errorNum = NoIpApiException::ABUSE;
                break;
            case '911':
                $error = 'A fatal error on our side such as a database outage. Retry the update no sooner 30 minutes.';
                $errorNum = NoIpApiException::FATAL;
                break;
            default:
                $error = 'Unknown error';
                $errorNum = NoIpApiException::UNKNOWN;
                break;
        }

        throw new NoIpApiException($error, $errorNum);
    }


}