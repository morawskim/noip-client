#!/usr/bin/env php
<?php

Phar::mapPhar();

include "phar://noip.phar/src/application.php";

__HALT_COMPILER();

?>