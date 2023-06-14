<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require 'getEvents.php';
require 'getEden.php';
require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

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

    $raw = getEvents($latitude, $longitude);
    $events = [];
    $matches = [];

    if ($raw !== null) {
        if (isset($raw['data']['poi']['results']) && is_array($raw['data']['poi']['results'])) {
            foreach ($raw['data']['poi']['results'] as $result) {
                $event = array(
                    "title" => isset($result['rdfs_label'][0]) ? $result['rdfs_label'][0] : '',
                    "link" => isset($result['_uri']) ? $result['_uri'] : '',
                    "description" => isset($result['hasDescription'][0]['shortDescription'][0]) ? $result['hasDescription'][0]['shortDescription'][0] : '',
                    "latitude" => isset($result['isLocatedAt'][0]['schema_geo'][0]['schema_latitude'][0]) ? $result['isLocatedAt'][0]['schema_geo'][0]['schema_latitude'][0] : '',
                    "longitude" => isset($result['isLocatedAt'][0]['schema_geo'][0]['schema_longitude'][0]) ? $result['isLocatedAt'][0]['schema_geo'][0]['schema_longitude'][0] : ''
                );
                $events[] = $event;
            }
        }

        $illustration = null;
        //$label = null;
        $tarif = null;
        $tarif_from = null;
        $distance = null;

        foreach ($events as $eve){
            array_push($matches,getEden($eve['title'],explode(' ', $keyword)));
        }

        for ($i = 0; $i < count($matches); $i++) {
            $j = $i;
            while ($j > 0 && $matches[$j - 1] < $matches[$j]) {
                $tempMatch = $matches[$j - 1];
                $tempEvent = $events[$j - 1];
        
                $matches[$j - 1] = $matches[$j];
                $events[$j - 1] = $events[$j];
        
                $matches[$j] = $tempMatch;
                $events[$j] = $tempEvent;
        
                $j--;
            }
        }        
        
    }


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

    $response = $response->withHeader('Content-Type', 'application/json; charset=UTF-8;');
    $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE));
    return $response;
});

// Wildcard route handler for all other routes
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