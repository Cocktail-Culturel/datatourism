<?php

function getEvents($keywords)
{

  // composer autoload
  require __DIR__ . '/vendor/autoload.php';
  // instanciation du client
  $blazegraphHostname = getenv('BLAZEGRAPH_HOSTNAME') ?: 'datatourism-bdd.cocktail-culture';
  $api = \Datatourisme\Api\DatatourismeApi::create("http://$blazegraphHostname/blazegraph/namespace/kb/sparql");

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

//$result_ = getEvents("Mike Marino Matt Osbourne Christian Stropko Annie Sperling Matt Ardine Official Music Video the Weeknd Your Tears");
//var_dump($result_);
