#!/usr/bin/env php

<?php

// composer autoload
require __DIR__ . '/vendor/autoload.php';

// instanciation du client
$api = \Datatourisme\Api\DatatourismeApi::create('http://localhost:9999/blazegraph/namespace/kb/sparql');

// éxecution d'une requête
$result = $api->process('{poi { results{ rdfs_label } } }');

// prévisualisation des résultats
var_dump($result);
