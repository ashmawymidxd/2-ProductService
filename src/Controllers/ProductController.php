<?php

namespace App\Controllers;

use App\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;

class ProductController
{
    public function index(Request $request, Response $response, $args)
    {
        $products = Product::all();
        $response->getBody()->write($products->toJson());
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function show(Request $request, Response $response, $args)
    {
        $product = Product::find($args['id']);
        if ($product) {
            $response->getBody()->write($product->toJson());
            return $response->withHeader('Content-Type', 'application/json');
        }
        return $response->withStatus(404);
    }

    public function create(Request $request, Response $response, $args)
    {
        // Define validation rules
        $validator = v::key('name', v::stringType()->notEmpty())
            ->key('price', v::floatVal()->notEmpty())
            ->key('category_id', v::intVal()->notEmpty());
        // Validate the data
        try{
            $data = $request->getParsedBody();
            $validator->assert($data);
            $product = Product::create($data);
            $response->getBody()->write($product->toJson());
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (\Respect\Validation\Exceptions\ValidationException $e) {
            $errors = $e->getMessages();
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
       
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $product = Product::find($args['id']);
        if ($product) {
            $product->update($data);
            $response->getBody()->write($product->toJson());
            return $response->withHeader('Content-Type', 'application/json');
        }
        return $response->withStatus(404);
    }

    public function delete(Request $request, Response $response, $args)
    {
        $product = Product::find($args['id']);
        if ($product) {
            $product->delete();
            return $response->withStatus(204);
        }
        return $response->withStatus(404);
    }
}
