<?php

namespace noip\Models;


use Symfony\Component\Validator\Validation;

class NoIpAccount
{
    /**
     * NoIP account username
     *
     * @var string
     */
    private $username = '';

    /**
     * NoIp account password
     *
     * @var string
     */
    private $password = '';

    /**
     * NoIp hostname
     *
     * @var string
     */
    private $hostName = '';

    public function __construct($username = null, $password = null, $hostName = null)
    {
        if (null !== $username) {
            $this->setUsername($username);
        }

        if (null !== $password) {
            $this->setPassword($password);
        }

        if (null !== $hostName) {
            $this->setHostName($hostName);
        }
    }


    /**
     * Get noip account password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set noip account password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get noip account username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set noip account username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get noip hostname assigned with account
     *
     * @return string
     */
    public function getHostName()
    {
        return $this->hostName;
    }

    /**
     * Set noip hostname assigned with account
     * @param string $host
     */
    public function setHostName($host)
    {
        $this->hostName = $host;
    }

    public function isValid()
    {
        $validator = Validation::createValidator();

//        $validator->validate()
    }
}