<?php

use Slim\Factory\AppFactory;
use Tuupola\Middleware\CorsMiddleware;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';

$app = AppFactory::create();

/*$app->add(new CorsMiddleware([
    "origin" => ["http://192.168.0.9:8000"], 
    //"methods" => ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
    "methods" => ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
    //"headers.allow" => ["Content-Type", "Authorization", "X-Requested-With", ],
    "headers.allow" => ["*"],
    "headers.expose" => [],
    "maxAge" => 3600,
    "credentials" => true,
]));*/



/*$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', 'http://192.168.0.9:8000')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Credentials', 'true');
});

$app->options('/{routes:.+}', function ($request, $response) {
    return $response;
});*/




require __DIR__ . '/../routes/404.php';
require __DIR__ . '/../routes/home.php';
require __DIR__ . '/../routes/fila.php';
require __DIR__ . '/../routes/produtos.php';

$app->run();
