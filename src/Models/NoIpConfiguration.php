<?php

namespace noip\Models;


use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\FormatException;

class NoIpConfiguration
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $hostname;

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $filePath Path to .env file
     * @throws FormatException
     * @return NoIpConfiguration
     */
    public static function parseDotEnvFile($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException(sprintf('File "%s" not exist', $filePath));
        }

        if (!is_readable($filePath)) {
            throw new \InvalidArgumentException(sprintf('File "%s" is not readable', $filePath));
        }

        if (!is_file($filePath)) {
            throw new \InvalidArgumentException(sprintf('File "%s" is not regular file', $filePath));
        }

        $dotEnv = new Dotenv();
        $array = $dotEnv->parse(file_get_contents($filePath), $filePath);

        return self::parseArray($array);
    }

    public static function parseArray(array $config)
    {
        $obj = new static;

        if (!empty($config['NOIP_USERNAME'])) {
            $obj->setUsername($config['NOIP_USERNAME']);
        }

        if (!empty($config['NOIP_PASSWORD'])) {
            $obj->setPassword($config['NOIP_PASSWORD']);
        }

        if (!empty($config['NOIP_HOSTNAME'])) {
            $obj->setHostname($config['NOIP_HOSTNAME']);
        }

        return $obj;
    }
}