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

    //$illustration = null;

    return sortData($events,$keyword);
}


function sortData($events,$userKeywords){

    $userKeywordsLower = array_map('strtolower', explode(' ',$userKeywords));

    $matches = [];

    for($i=0;$i<count($events);$i++){
        
        $keywords= strtolower($events[$i]['title']);
        $description= strtolower($events[$i]['description']);
        
        
        $keywordWords = explode(' ', $keywords);
        $descriptionWords = explode(' ', $description);
        $isMatch=false;
        

        $j=0;
        while($j<count($keywordWords) && !$isMatch){
            if (in_array($keywordWords[$j], $userKeywordsLower) || in_array($descriptionWords[$j], $userKeywordsLower)) {
                $isMatch=true;
            }else{
                $j++;
            }
        }

        if($isMatch){
            array_push($matches,$events[$i]);
        }
    }

    return $matches;
}

?>
