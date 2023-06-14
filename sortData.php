<?php

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
    
    return $events;
}

?>
