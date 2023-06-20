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
    for ($i = 0; $i < count($userKeywordsLower); $i++) {
        $userKeywordsLower[$i] = str_replace('_', ' ', $userKeywordsLower[$i]);
    }
    
    $matches = [];

    for($i=0;$i<count($events);$i++){
        
        $keywords= strtolower($events[$i]['title']);
        $description= strtolower($events[$i]['description']);
        
        
        $keywordWords = explode(' ', $keywords);

        $descriptionWords = explode(' ', $description);

        $isMatch=false;

        $k = 0;
        $d = 0;
        $keywordCount = count($keywordWords);
        $descriptionCount = count($descriptionWords);
        
        while (($k < $keywordCount || $d < $descriptionCount) && !$isMatch) {
            if ($k < $keywordCount && in_array($keywordWords[$k], $userKeywordsLower)) {
                $isMatch = true;
            } elseif ($d < $descriptionCount && in_array($descriptionWords[$d], $userKeywordsLower)) {
                $isMatch = true;
            } elseif ($k > 0 && in_array($keywordWords[$k - 1] . ' ' . $keywordWords[$k], $userKeywordsLower)) {
                $isMatch = true;
            }
              elseif ($k > 0 && in_array($descriptionWords[$k - 1] . ' ' . $descriptionWords[$k], $userKeywordsLower)) {
                $isMatch = true;
            }
            
            $k++;
            $d++;
        }
        
        
        if($isMatch){
            array_push($matches,$events[$i]);
        }
    }

    return $matches;
}

?>
