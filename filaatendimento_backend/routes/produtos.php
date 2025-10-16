<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\System\MyClasse;
use App\Api\ApiReceiver;



$app->get('/teste', function ($request, $response, $args) {
    $myClass = new MyClasse();
    $response->getBody()->write($myClass->sayHello());
    return $response;
});


$app->get('/api', function (Request $request, Response $response, $args) {

    $receiver = new ApiReceiver();

    $id_clinica_veterinaria_fk = '468';
    $id_usuario = '1816';
    $timestamp = time();
    $hash = 'ZGUwOTVlMWFhMjZhMDkyODliZmE1NDJhYjdiY2VkYzc3NDBk5YzdiY2I4NDNkZWVkMA==';

    if ($receiver->processRequest($id_clinica_veterinaria_fk, $id_usuario, $timestamp, $hash)) {
        $response->getBody()->write(json_encode(['success' => 'Verification passed']));
    } else {
        $response->getBody()->write(json_encode(['error' => 'Verification failed']));
    }

    return $response->withHeader('Content-Type', 'application/json');
    
});


$app->get('/produtos', function ($request, $response, $args) {
    
    $produtos = [
        ["id" => 1, "nome" => "Produto 1", "preco" => 10.00],
        ["id" => 2, "nome" => "Produto 2", "preco" => 12.50],
        ["id" => 3, "nome" => "Produto 3", "preco" => 8.99],
        ["id" => 4, "nome" => "Produto 4", "preco" => 15.00],
        ["id" => 5, "nome" => "Produto 5", "preco" => 9.75],
        ["id" => 6, "nome" => "Produto 6", "preco" => 11.50],
        ["id" => 7, "nome" => "Produto 7", "preco" => 13.25],
        ["id" => 8, "nome" => "Produto 8", "preco" => 14.00],
        ["id" => 9, "nome" => "Produto 9", "preco" => 7.25],
        ["id" => 10, "nome" => "Produto 10", "preco" => 6.50],
        ["id" => 11, "nome" => "Produto 11", "preco" => 10.25],
        ["id" => 12, "nome" => "Produto 12", "preco" => 12.00],
        ["id" => 13, "nome" => "Produto 13", "preco" => 13.75],
        ["id" => 14, "nome" => "Produto 14", "preco" => 11.00],
        ["id" => 15, "nome" => "Produto 15", "preco" => 9.50],
        ["id" => 16, "nome" => "Produto 16", "preco" => 10.75],
        ["id" => 17, "nome" => "Produto 17", "preco" => 8.00],
        ["id" => 18, "nome" => "Produto 18", "preco" => 14.50],
        ["id" => 19, "nome" => "Produto 19", "preco" => 12.25],
        ["id" => 20, "nome" => "Produto 20", "preco" => 15.75],
    ];

    $response = $response->withHeader('Content-Type', 'application/json');
    $response->getBody()->write(json_encode($produtos));
    return $response;
});
