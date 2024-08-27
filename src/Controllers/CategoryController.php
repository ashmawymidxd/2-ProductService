<?php

namespace App\Controllers;

use App\Models\Category;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;

class CategoryController
{
    public function index(Request $request, Response $response, $args)
    {
        $categories = Category::all();
        $response->getBody()->write($categories->toJson());
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function show(Request $request, Response $response, $args)
    {
        $category = Category::find($args['id']);
        if ($category) {
            $response->getBody()->write($category->toJson());
            return $response->withHeader('Content-Type', 'application/json');
        }
        return $response->withStatus(404);
    }

    public function create(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();

        // Define validation rules
        $validator = v::key('name', v::stringType()->notEmpty());

        // Validate the data
        try {
            $validator->assert($data);

            // Data is valid, create the category
            $category = Category::create($data);
            $response->getBody()->write($category->toJson());
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

        } catch (\Respect\Validation\Exceptions\ValidationException $e) {
            // Data is invalid, return validation errors
            $errors = $e->getMessages();
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $category = Category::find($args['id']);
        if ($category) {
            $category->update($data);
            $response->getBody()->write($category->toJson());
            return $response->withHeader('Content-Type', 'application/json');
        }
        return $response->withStatus(404);
    }

    public function delete(Request $request, Response $response, $args)
    {
        $category = Category::find($args['id']);
        if ($category) {
            $category->delete();
            return $response->withHeader('Content-Type', 'application/json')->withStatus(204);
        }
        return $response->withStatus(404);
    }
}
