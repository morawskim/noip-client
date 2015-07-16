<?php

namespace noip\Models;


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

    public static function parseIniFile($filePath)
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

        $array = parse_ini_file($filePath, true);
        if (false === $array) {
            throw new \RuntimeException(sprintf('Cant parse INI "%s" file'));
        }

        return self::parseArray($array);
    }

    public static function parseArray(array $config)
    {
        $obj = new static;

        if (isset($config['noip']) && is_array($config['noip'])) {
            $array = $config['noip'];
        } else {
            $array = array();
        }

        if (!empty($array['username'])) {
            $obj->setUsername($array['username']);
        }

        if (!empty($array['password'])) {
            $obj->setPassword($array['password']);
        }

        if (!empty($array['hostname'])) {
            $obj->setHostname($array['hostname']);
        }

        return $obj;
    }
}