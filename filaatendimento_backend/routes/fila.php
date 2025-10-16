<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Database\Database;
use App\Api\ApiReceiver;
use App\Middleware\AuthorizationMiddleware;
use Slim\Routing\RouteCollectorProxy;


$app->group('/app/list', function (Slim\Routing\RouteCollectorProxy $group) {

    $group->get('/last_results', function ($request, $response, $args) {
        $token_id_clinica = $request->getHeaderLine('token_id_clinica');
        $dbConnection = Database::getInstance()->getConnection();

        $stmt = $dbConnection->prepare("SELECT * FROM (SELECT * FROM tbfila WHERE id_clinica_veterinaria = :id_clinica_veterinaria ORDER BY created_at DESC LIMIT 10) AS últimos_registros ORDER BY priority DESC;");
        $stmt->bindValue(':id_clinica_veterinaria', $token_id_clinica, PDO::PARAM_INT);
        $stmt->execute();

        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($produtos));

        return $response;
    });


    $group->post('/', function ($request, $response, $args) {
        try {
            $token_id_user = $request->getHeaderLine('token_id_user');
            $token_id_clinica = $request->getHeaderLine('token_id_clinica');
            $token_timestamps = $request->getHeaderLine('token_timestamps');
            $token_amb = $request->getHeaderLine('token_amb');
            $token_hash = $request->getHeaderLine('token_hash');

            $authorization = new ApiReceiver;
            $verify = $authorization->processRequest($token_id_clinica, $token_id_user, $token_timestamps, $token_hash);

            if (!$verify) {
                throw new \Exception("Erro na verificação");
            }

            $data = $request->getParsedBody();

            $dbConnection = Database::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("INSERT INTO tbfila (id_clinica_veterinaria, id_cliente, id_animal, id_funcionario, name_room, priority, obs) VALUES (:id_clinica_veterinaria, :id_cliente, :id_animal, :id_funcionario, :name_room, :priority, :obs)");
            $stmt->bindValue(':id_clinica_veterinaria', $data['id_clinica_veterinaria'], PDO::PARAM_INT);
            $stmt->bindValue(':id_cliente', $data['id_cliente'], PDO::PARAM_INT);
            $stmt->bindValue(':id_animal', $data['id_animal'], PDO::PARAM_INT);
            $stmt->bindValue(':id_funcionario', $data['id_funcionario'], PDO::PARAM_INT);
            $stmt->bindValue(':name_room', $data['name_room'], PDO::PARAM_STR);
            $stmt->bindValue(':priority', $data['priority'], PDO::PARAM_INT);
            $stmt->bindValue(':obs', $data['obs'], PDO::PARAM_STR);
            $stmt->execute();

            $response->getBody()->write(json_encode(['success' => 1, 'message' => "Adicionado com sucesso"]));
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['success' => 0, 'message' => $e->getMessage()]));
        }

        return $response;
    });


    $group->delete('/{id}', function ($request, $response, $args) {
        try {
            $token_id_user = $request->getHeaderLine('token_id_user');
            $token_id_clinica = $request->getHeaderLine('token_id_clinica');
            $token_timestamps = $request->getHeaderLine('token_timestamps');
            $token_amb = $request->getHeaderLine('token_amb');
            $token_hash = $request->getHeaderLine('token_hash');

            $authorization = new ApiReceiver;
            $verify = $authorization->processRequest($token_id_clinica, $token_id_user, $token_timestamps, $token_hash);

            if (!$verify) {
                throw new \Exception("Erro na verificação");
            }

            $id = (int)$args['id'];

            $dbConnection = Database::getInstance()->getConnection();
            $stmt = $dbConnection->prepare("DELETE FROM tbfila WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();


            $response->getBody()->write(json_encode(['success' => 1, 'message' => "Deletado com sucesso"]));
        } catch (\Exception $e) {

            $response->getBody()->write(json_encode(['success' => 0, 'message' => $e->getMessage()]));
        }
        return $response;
    });



    $group->get('/{id}', function ($request, $response, $args) {

        try {
            $token_id_user = $request->getHeaderLine('token_id_user');
            $token_id_clinica = $request->getHeaderLine('token_id_clinica');
            $token_timestamps = $request->getHeaderLine('token_timestamps');
            $token_amb = $request->getHeaderLine('token_amb');
            $token_hash = $request->getHeaderLine('token_hash');

            $authorization = new ApiReceiver;
            $verify = $authorization->processRequest($token_id_clinica, $token_id_user, $token_timestamps, $token_hash);

            if (!$verify) {
                throw new \Exception("Erro na verificação");
            }

            $id = (int)$args['id'];

            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM tbfila WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            $response->getBody()->write(json_encode($item));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {

            $response->getBody()->write(json_encode(['success' => 0, 'message' => $e->getMessage()]));
        }
        return $response;
    });


    $group->put('/{id}', function ($request, $response, $args) {

        try {
            $token_id_user = $request->getHeaderLine('token_id_user');
            $token_id_clinica = $request->getHeaderLine('token_id_clinica');
            $token_timestamps = $request->getHeaderLine('token_timestamps');
            $token_amb = $request->getHeaderLine('token_amb');
            $token_hash = $request->getHeaderLine('token_hash');

            $authorization = new ApiReceiver;
            $verify = $authorization->processRequest($token_id_clinica, $token_id_user, $token_timestamps, $token_hash);

            if (!$verify) {
                throw new \Exception("Erro na verificação");
            }

            $id = (int)$args['id'];

            $data = json_decode($request->getBody()->getContents(), true);

            $dbConnection = Database::getInstance()->getConnection();
            $sql = "UPDATE tbfila SET 
                    id_clinica_veterinaria = :id_clinica_veterinaria, 
                    id_cliente = :id_cliente, 
                    id_animal = :id_animal, 
                    id_funcionario = :id_funcionario, 
                    name_room = :name_room, 
                    priority = :priority,
                    obs = :obs  
                    WHERE id = :id";

            try {
                $stmt = $dbConnection->prepare($sql);
                $stmt->execute([
                    ':id_clinica_veterinaria' => $data['id_clinica_veterinaria'],
                    ':id_cliente' => $data['id_cliente'],
                    ':id_animal' => $data['id_animal'],
                    ':id_funcionario' => $data['id_funcionario'],
                    ':name_room' => $data['name_room'],
                    ':priority' => $data['priority'],
                    ':obs' => $data['obs'],
                    ':id' => $id
                ]);

                $response->getBody()->write(json_encode(['success' => true]));
            } catch (\Exception $e) {
                $response->getBody()->write(json_encode(['success' => false, 'error' => $e->getMessage()]));
            }

            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {

            $response->getBody()->write(json_encode(['success' => 0, 'message' => $e->getMessage()]));
        }
        return $response;
    });
})->add(AuthorizationMiddleware::class);
