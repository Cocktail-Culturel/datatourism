<?php

function getEvents($latitude, $longitude,$radius)
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
                    lng: {$longitude} , lat: {$latitude} , distance: {$radius}  
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
                    lng: {$longitude} , lat: {$latitude} , distance: {$radius} 
                  } 
                }
              }
            }
        ]
    )
    
    {
      results {
        _uri
        rdfs_label {
          value
          lang
        }
        hasDescription {
          shortDescription {
            value
            lang
          }
        }
        isLocatedAt {
          schema_geo {
            schema_latitude
            schema_longitude
          }
        }
        hasMainRepresentation {
          _uri
        }
        offers {
          schema_priceSpecification {
            schema_price
            schema_minPrice
          }
        }
        takesPlaceAt{
          startDate
        }
      }
    }
}");

  
  return $result;
}

//$result_ = getEvents(48.87, 2.33,15);
//var_dump($result_);
?>