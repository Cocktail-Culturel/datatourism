<?php

function getDistance($lat1,$long1,$lat2,$long2){
    return round(sqrt(pow(($long2 - $long1),2) + pow(($lat2 - $lat1),2))*100,1);
}

function getData($raw,$keyword,$latitude,$longitude){
    $events = [];

    if (isset($raw['data']['poi']['results']) && is_array($raw['data']['poi']['results'])) {
        foreach ($raw['data']['poi']['results'] as $result) {
            $event = array(
                "title" => isset($result['rdfs_label'][0]['value']) ? $result['rdfs_label'][0]['value'] : '',
                "link" => isset($result['_uri']) ? $result['_uri'] : '',
                "description" => isset($result['hasDescription'][0]['shortDescription'][0]['value']) ? $result['hasDescription'][0]['shortDescription'][0]['value'] : '',
                "date" => isset(explode(' ',$result['takesPlaceAt'][0]['startDate'][0])[0]) ? explode(' ',$result['takesPlaceAt'][0]['startDate'][0])[0] : '',
                "tarif_from" => isset($result['offers'][0]['schema_priceSpecification'][0]['schema_minPrice'][0]) ? $result['offers'][0]['schema_priceSpecification'][0]['schema_minPrice'][0] : '',
                "distance" => getDistance($latitude,$longitude,$result['isLocatedAt'][0]['schema_geo'][0]['schema_latitude'][0],$result['isLocatedAt'][0]['schema_geo'][0]['schema_longitude'][0]),
                "latitude" => isset($result['isLocatedAt'][0]['schema_geo'][0]['schema_latitude'][0]) ? $result['isLocatedAt'][0]['schema_geo'][0]['schema_latitude'][0] : '',
                "longitude" => isset($result['isLocatedAt'][0]['schema_geo'][0]['schema_longitude'][0]) ? $result['isLocatedAt'][0]['schema_geo'][0]['schema_longitude'][0] : ''
                );
            $events[] = $event;
        }
    }

    $illustration = null;

    return sortData($events,$keyword);
}


function sortData($events,$userKeywords){

    $userKeywordsLower = array_map('strtolower', explode(' ',$userKeywords));

    $temp = [];

    foreach($events as $eve){
        array_push($temp,$eve['title']);
    }

    $keywords= array_map('strtolower', $temp);

    $matches = [];

    foreach ($keywords as $keyword) {

        // Split the keyword into individual words
        $keywordWords = explode(' ', $keyword);

        $match=0;
        // Compare each word of the keyword with your list of lowercase keywords
        foreach ($keywordWords as $word) {
            if (in_array($word, $userKeywordsLower)) {
                $match++;
            }
        }
        array_push($matches,$match);

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
    
    //echo count($matches);
    //return array_slice($events,0,count($matches));
    return $events;
}

?>
