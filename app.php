<?php

$configs = include 'app/configs.php';

$app = new App($configs);

$app->handleRequest();
$database = $app->createDatabaseInstance();


