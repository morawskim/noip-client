#!/usr/bin/env php
<?php

Phar::mapPhar('noip.phar');

include "phar://noip.phar/src/application.php";

__HALT_COMPILER();

?>
