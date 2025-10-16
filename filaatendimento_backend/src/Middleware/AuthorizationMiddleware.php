<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Response;
use App\Api\ApiReceiver;

class AuthorizationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Obtenha os headers necessários
        $tokenIdClinica = $request->getHeaderLine('token_id_clinica');
        $tokenIdUser = $request->getHeaderLine('token_id_user');
        $tokenTimestamps = $request->getHeaderLine('token_timestamps');
        $tokenHash = $request->getHeaderLine('token_hash');


        $authorization = new ApiReceiver();
        $verify = $authorization->processRequest($tokenIdClinica, $tokenIdUser, $tokenTimestamps, $tokenHash);

        if (!$verify) {
            // Retorna um erro 403 se a verificação falhar
            return (new Response())->withStatus(403);
        }

        // Continue para o próximo middleware ou handler
        return $handler->handle($request);
    }
}