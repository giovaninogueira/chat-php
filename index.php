<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Server' . DIRECTORY_SEPARATOR . 'server.php';
use Server\Server;
date_default_timezone_set('America/Sao_Paulo');
Server::runServer(8090, '127.0.0.1');