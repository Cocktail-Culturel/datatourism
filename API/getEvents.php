<?php

function getEvents($keywords)
{

  // composer autoload
  require __DIR__ . '/vendor/autoload.php';
  // instanciation du client
  $blazegraphHostname = getenv('BLAZEGRAPH_HOSTNAME') ?: 'localhost';
  $api = \Datatourisme\Api\DatatourismeApi::create("http://datatourism-bdd.cocktail-culturel.com/blazegraph/namespace/kb/sparql");

  // Requete data
  $result = $api->process('{
    poi(
        filters: [
          {
            _or : [
              {
              hasDescription: 
              {
                shortDescription: 
                {
                  _text: "'. $keywords.'"
                }
              }
              },
              {
                rdfs_label:
                {
                  _text:"'. $keywords.'"
                }
              }
            ]
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
}');


  return $result;
}

#$result_ = getEvents("the weeknd");
#var_dump($result_);
