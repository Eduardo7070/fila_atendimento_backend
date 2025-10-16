<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Database\Database;


$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello Worlda");
    return $response;
});
