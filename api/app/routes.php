<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Doctrine\ORM\EntityManager; // Importante
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $container = require __DIR__ . '/connectiondb.php';

    $app->get('/', function (Request $request, Response $response) {
        $response_array = [ 'message' => 'Hello world! dese api Slim'];
        $respose_string = json_encode($response_array);
        $response->getBody()->write($respose_string);
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    $app->get('/categories', function (Request $request, Response $response) use ($container) {
        // Obtiene el EntityManager de Doctrine
        $entityManager = $container->get(EntityManager::class);
    
        // Obtiene la conexión a la base de datos de Doctrine DBAL
        $connection = $entityManager->getConnection();
    
        // Crea un nuevo QueryBuilder
        $queryBuilder = $connection->createQueryBuilder();
    
        // Construye la consulta SQL
        $queryBuilder->select('*')->from('plan_category');
    
        // Ejecuta la consulta y obtiene todos los resultados
        $categories = $queryBuilder->execute()->fetchAll();
    
        // Convierte los resultados a JSON y los escribe en el cuerpo de la respuesta
        $response->getBody()->write(json_encode($categories));
    
        return $response->withHeader('Content-Type', 'application/json');
    });
};
