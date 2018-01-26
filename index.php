<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Server' . DIRECTORY_SEPARATOR . 'server.php';
use Server\Server;

Server::runServer();