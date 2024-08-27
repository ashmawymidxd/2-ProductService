<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\ProductController;
use App\Controllers\CategoryController;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as Capsule;

$app->group('/api', function (RouteCollectorProxy $group) {
    $group->group('/products', function (RouteCollectorProxy $group) {
        $group->get('', ProductController::class . ':index');
        $group->get('/{id}', ProductController::class . ':show');
        $group->post('', ProductController::class . ':create');
        $group->put('/{id}', ProductController::class . ':update');
        $group->delete('/{id}', ProductController::class . ':delete');
    });

    $group->group('/categories', function (RouteCollectorProxy $group) {
        $group->get('', CategoryController::class . ':index');
        $group->get('/{id}', CategoryController::class . ':show');
        $group->post('', CategoryController::class . ':create');
        $group->put('/{id}', CategoryController::class . ':update');
        $group->delete('/{id}', CategoryController::class . ':delete');
    });
});

$app->get('/', function (Request $request, Response $response, $args) {
    try {
        $results = Capsule::select('SELECT DATABASE() AS db');
        $response->getBody()->write(json_encode($results));
    } catch (\Exception $e) {
        $response->getBody()->write($e->getMessage());
    }

    return $response->withHeader('Content-Type', 'application/json');
});