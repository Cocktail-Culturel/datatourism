<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

error_reporting(E_ALL & ~E_DEPRECATED);

require 'getEvents.php';
require 'Data.php';
require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(false, false, false);

$app->get('/', function (Request $request, Response $response, $args) {
    $responseData = array(
        'status' => 404,
        'message' => 'API dataTourism, regarder la documentation'
    );

    $response = $response->withHeader('Content-Type', 'application/json; charset=UTF-8;');
    $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE));
    return $response;
});


$app->get('/api', function (Request $request, Response $response, $args) {
    $latitude = $request->getQueryParams()['latitude'] ?? null;
    $longitude = $request->getQueryParams()['longitude'] ?? null;
    $keyword = $request->getQueryParams()['keyword'] ?? null;

    /*if (strpos($keyword, '_') !== false) {
        $raw = getEvents(str_replace('_', ' ', $keyword));
    } else {
        $raw = getEvents($keyword);
    }*/

    $raw = getEvents($keyword);

    if ($raw !== null) {
        $Events=getData($raw,$keyword,$latitude,$longitude);
        $responseData = array(
            'status' => 200,
            'message' => 'Success',
            'nb_results'=> count($Events),
            'result' => $Events
        );
    }else{
        $responseData = array(
            'status' => 200,
            'message' => 'Success',
            'nb_results'=> 0,
            'result' => []
        );
    }

    $response = $response->withHeader('Content-Type', 'application/json; charset=UTF-8;');
    $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE));
    return $response;
});

$app->any('/{route:.+}', function (Request $request, Response $response) {
    $responseData = array(
        'status' => 404,
        'message' => 'Route invalide, regarder la documentation'
    );
    $response = $response->withHeader('Content-Type', 'application/json; charset=UTF-8;');
    $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE));
    return $response;
});

$app->run();