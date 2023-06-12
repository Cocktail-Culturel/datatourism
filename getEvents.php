#!/usr/bin/env php

<?php


function getEvents($latitude, $longitude)
{
  // A améliorer?
  $latitudeMin = strval($latitude -0.5);
  $latitudeMax = strval($latitude +0.5);
  $longitudeMin = strval($longitude -0.5);
  $longitudeMax = strval($longitude +0.5);

  // composer autoload
  require __DIR__ . '/vendor/autoload.php';
  // instanciation du client
  $api = \Datatourisme\Api\DatatourismeApi::create('http://192.168.58.131:9000/blazegraph/namespace/kb/sparql');

  // éxecution d'une requête
  $result = $api->process("{
  poi(
    filters: [
      { _and : [
        {
          isLocatedAt : {
            schema_geo : {
              schema_latitude : {
                _gte:{$latitudeMin}
              }
              schema_longitude : {
                _gte:{$longitudeMin}
              }
              
            }
          }
        }
        {
          isLocatedAt : {
            schema_geo : {
              schema_latitude : {
                _lte:{$latitudeMax}
              }
              schema_longitude : {
                _lte: {$longitudeMax}
              }
              
            }
          }
        }
      ]
      }
    ]
  )
  {
    results {
      _uri  
      rdfs_label 
      hasDescription {
        shortDescription    # <- Description du POI
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

  // prévisualisation des résultats
  return $result;
}

$result_ = getEvents(45, 4);
var_dump($result_);
?>