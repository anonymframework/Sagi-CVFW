<?php

$configs = include 'app/configs.php';

$app = new App($configs);


$database = $app->createDatabaseInstance();
Controller::$db = $database;

$view = $app->createViewInstance();
Controller::setViewInstance($view);

$app->handleRequest();



