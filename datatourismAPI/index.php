<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response, $args) {
    $responseData = array(
        'status' => 200,
        'message' => 'API dataTourism, regarder la documentation'
    );

    $response = $response->withHeader('Content-Type', 'application/json');
    $response->getBody()->write(json_encode($responseData));
    return $response;
});

$app->get('/api', function (Request $request, Response $response, $args) {
    $latitude = $request->getQueryParams()['latitude'] ?? null;
    $longitude = $request->getQueryParams()['longitude'] ?? null;
    $keyword = $request->getQueryParams()['keyword'] ?? null;

    $data = array(
        'latitude' => $latitude,
        'longitude' => $longitude,
        'keyword' => $keyword
    );

    $responseData = array(
        'status' => 200,
        'message' => 'Success',
        'data' => $data
    );

    $response = $response->withHeader('Content-Type', 'application/json');
    $response->getBody()->write(json_encode($responseData));
    return $response;
});

// Wildcard route handler for all other routes
$app->any('/{route:.+}', function (Request $request, Response $response) {
    $responseData = array(
        'status' => 404,
        'message' => 'Route invalide, regarder la documentation'
    );
    $response = $response->withHeader('Content-Type', 'application/json');
    $response->getBody()->write(json_encode($responseData));
    return $response;
});

$app->run();