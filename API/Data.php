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
    $mots=$userKeywordsLower;
    $pattern = '/[^a-zA-Zéàêèçù\s\']+/u';
    
    for($i=0;$i<count($events);$i++){
        
        $keywords= strtolower($events[$i]['title']);
        $description= strtolower($events[$i]['description']);

        $keywordWords = explode(' ', preg_replace($pattern, '', $keywords));
        $descriptionWords = explode(' ', preg_replace($pattern, '', $description));

        $isMatch=false;
        $isMatchdes=false;

        $j=0;
        while($j<count($keywordWords)){
            $k=0;
            $continue=true;
            while($k<count($mots) && $continue){
                if($keywordWords[$j+$k]==$mots[$k]){
                    $continue=true;
                }else{
                    $continue=false;
                }
                $k++;
            }
            if($continue){
                $isMatch=true;
                break;
            }
            $j++;
        }

        $j=0;
        while($j<count($descriptionWords)){
            $k=0;
            $continue=true;
            while($k<count($mots) && $continue){
                if($descriptionWords[$j+$k]==$mots[$k]){
                    $continue=true;
                }else{
                    $continue=false;
                }
                $k++;
            }
            if($continue){
                $isMatchdes=true;
                break;
            }
            $j++;
        }

        if($isMatch || $isMatchdes){
            array_push($matches,$events[$i]);
        }
        
    }

    for($i=0;$i<count($matches);$i++){

        $j=$i;

        while($j>0 && $matches[$j-1]['distance']>$matches[$j]['distance']){

            $temp=$matches[$j-1];
            $matches[$j-1]=$matches[$j];
            $matches[$j]=$temp;

            $j--;
        }
        
    }


    return $matches;
}

?>
