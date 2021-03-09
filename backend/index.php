<?php
require_once(__DIR__."/vendor/autoload.php");

use takisrs\VacationApp;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

(new VacationApp)->init();