<?php

function getEvents($latitude, $longitude)
{

  // composer autoload
  require __DIR__ . '/vendor/autoload.php';
  // instanciation du client
  $blazegraphHostname = getenv('BLAZEGRAPH_HOSTNAME') ?: 'localhost';
  $api = \Datatourisme\Api\DatatourismeApi::create("http://$blazegraphHostname:9999/blazegraph/namespace/kb/sparql");


  // Requete total
  $data = $api->process("
  {
    poi(
        filters: [
            { 
              isLocatedAt: 
              {
                schema_geo: 
                { 
                  _geo_distance: 
                  {
                    lng: {$longitude} , lat: {$latitude} , distance: \"15\"  
                  } 
                }
              }
            }
        ]
    )
    
  {
    total
  } 
}");
$total = $data["data"]["poi"]["total"];

  // Requete data
  $result = $api->process("{
    poi(
      size : {$total},
        filters: [
            { 
              isLocatedAt: 
              {
                schema_geo: 
                { 
                  _geo_distance: 
                  {
                    lng: {$longitude} , lat: {$latitude} , distance: \"10\" 
                  } 
                }
              }
            }
        ]
    )
    
  {
    total
    results {
      rdfs_label
      hasDescription{
        shortDescription{
          value
          lang
        }
      }
      isLocatedAt {
        schema_geo {
          schema_latitude   # <- Latitude du POI
          schema_longitude  # <- Longitude du POI
        }
      }
    } 
      
  } 
}");

  
  return $result;
}

//$result_ = getEvents(48.87, 2.33);
//var_dump($result_);
?>