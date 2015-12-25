<?php
define(ROOT_PATH, __DIR__);
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'Config.php';
include 'autoload.php';
echo app::I()->IndexRun();
