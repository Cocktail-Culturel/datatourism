#!/bin/bash

# Vérification si le nom de la région est passé en tant que paramètre
if [[ -z $1 ]]; then
    echo "Veuillez fournir le nom de la région en tant que paramètre."
    exit 1
fi

# Lecture du fichier flux.json
data=$(cat flux.json)

# Extraction de l'URL correspondant au nom de la région
region_name=$1
url=$(echo "$data" | jq -r '."'$region_name'"')

# Vérification si l'URL est définie
if [[ -z $url ]]; then
    echo "L'URL pour la région '$region_name' n'est pas définie dans le fichier flux.json."
    exit 1
fi

modified_url="$url"bb3e487f-7b9f-4085-b409-a530cbe2fb90

echo "Téléchargement de $modified_url ..."
wget "$modified_url" -O flux.gz

echo "Décompression de flux.gz ..."
gzip -d flux.gz

echo "Renommage du fichier décompressé en flux.rdf ..."
mv flux flux.rdf

echo "Envoi de flux.rdf vers https://datatourism-bdd.cocktail-culturel.com/blazegraph/namespace/kb/sparql ..."
curl -X POST -H "Content-Type:application/rdf+xml" --data-binary @flux.rdf "https://datatourism-bdd.cocktail-culturel.com/blazegraph/namespace/kb/sparql"

echo "Traitement terminé pour la région '$region_name'."
