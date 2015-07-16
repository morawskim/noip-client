<?php

namespace noip\Exception;


class NoIpApiException extends \Exception implements INoIpApiException
{
    const NOHOST = 1;
    const BADAUTH = 2;
    const BADAGENT = 3;
    const DONATOR = 4;
    const ABUSE = 5;
    const FATAL = 6;
    const UNKNOWN = 7;
}