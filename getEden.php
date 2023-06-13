<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

function getEden($text,$userKeywords){
    $url = "https://api.edenai.run/v2/text/keyword_extraction";

    $data = array(
        'text' => $text,
        'providers' => 'ibm',
        'language' => 'fr'
    );

    $api_key = $_ENV['EDEN_API_KEY'];

    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $api_key,
        'Content-Type: application/json',
        'Accept: application/json'
    ));

    $response = curl_exec($ch);

    if(curl_errno($ch)){
        $error_message = curl_error($ch);
        echo $error_message;
    }

    curl_close($ch);


    $userKeywordsLower = array_map('strtolower', $userKeywords);

    if ($response) {
        $result = json_decode($response, true);
        $keywords = $result['eden-ai']['items'];

        // Process each keyword individually

        $match=0;
        foreach ($keywords as $keyword) {
            $keywordText = $keyword['keyword'];

            // Split the keyword into individual words
            $keywordWords = explode(' ', $keywordText);

            // Flag to track if any matching keyword is found
            $matchFound = false;

            // Compare each word of the keyword with your list of lowercase keywords
            foreach ($keywordWords as $word) {
                $lowercaseWord = strtolower($word);
                if (in_array($lowercaseWord, $userKeywordsLower)) {
                    //echo "Match found for keyword: " . $word . "\n";
                    $matchFound = true;
                    $match++;
                    break; // Exit the inner loop if a match is found
                }
            }
        }
    }
    return $match;
}

?>
