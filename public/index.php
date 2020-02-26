<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../config/helpers.php';
require_once '../app/components/cart.php';
require '../bootstrap/app.php';
require '../bootstrap/container.php';
require '../routes/web.php';
$app->run();

