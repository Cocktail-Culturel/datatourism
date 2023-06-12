<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require 'getEvents.php';
require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(false, true, false);

$app->get('/', function (Request $request, Response $response, $args) {
    $responseData = array(
        'status' => 404,
        'message' => 'API dataTourism, regarder la documentation'
    );

    $response = $response->withHeader('Content-Type', 'text/json; charset=UTF-8;');
    $response->getBody()->write(json_encode($responseData));
    return $response;
});


$app->get('/api', function (Request $request, Response $response, $args) {
    $latitude = $request->getQueryParams()['latitude'] ?? null;
    $longitude = $request->getQueryParams()['longitude'] ?? null;
    $keyword = $request->getQueryParams()['keyword'] ?? null;

    $raw= getEvents($latitude, $longitude);
    $events = array();

    foreach ($raw['data']['poi']['results'] as $result) {
        $event = array(
            "title" => $result['rdfs_label'][0],
            "link" => $result['_uri'],
            "description" => $result['hasDescription'][0]['shortDescription'][0],
            "latitude" => $result['isLocatedAt'][0]['schema_geo'][0]['schema_latitude'][0],
            "longitude" => $result['isLocatedAt'][0]['schema_geo'][0]['schema_longitude'][0]
        );
        $events[] = $event;
    }

    $illustration=null;
    //$label=null;
    $tarif=null;
    $tarif_from=null;
    $distance=null;

    /*$data = array(
        'latitude' => $latitude,
        'longitude' => $longitude,
        'keyword' => $keyword,
        'title'=>$title,
        'link'=>$link,
        'illustration'=>$illustration,
        'description'=>$description,
        'label'=>$label,
        'tarif'=>$tarif,
        'tarif_from'=>$tarif_from,
        'distance'=>$distance
    );*/



    $responseData = array(
        'status' => 200,
        'message' => 'Success',
        'result' => $events
    );

    $response = $response->withHeader('Content-Type', 'text/json; charset=UTF-8;');
    $response->getBody()->write(json_encode($responseData));
    return $response;
});

// Wildcard route handler for all other routes
$app->any('/{route:.+}', function (Request $request, Response $response) {
    $responseData = array(
        'status' => 404,
        'message' => 'Route invalide, regarder la documentation'
    );
    $response = $response->withHeader('Content-Type', 'text/json; charset=UTF-8;');
    $response->getBody()->write(json_encode($responseData));
    return $response;
});

$app->run();