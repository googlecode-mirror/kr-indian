<?php

ob_start();

define('APP_DIR', __DIR__ . '/');

require_once('./include/init.php');

App::run();
?>