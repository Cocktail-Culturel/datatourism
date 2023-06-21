#!/bin/bash

# Vérification si l'indice est passé en tant que paramètre
if [[ -z $1 ]]; then
    echo "Veuillez fournir l'indice de l'URL en tant que paramètre."
    exit 1
fi

# Lecture du fichier flux.json
data=$(cat flux.json)

# Extraction des clés (indices) du fichier JSON
indices=($(echo "$data" | jq -r 'keys[]'))

# Vérification si l'indice est valide
index=$1
if [[ $index -lt 0 || $index -ge ${#indices[@]} ]]; then
    echo "Indice invalide. Veuillez choisir un indice entre 0 et $(( ${#indices[@]} - 1 ))."
    exit 1
fi

# Extraction de l'URL correspondant à l'indice
key=${indices[$index]}
url=$(echo "$data" | jq -r '."'$key'"')

# Vérification si l'URL est définie
if [[ -z $url ]]; then
    echo "L'URL correspondant à l'indice $index n'est pas définie dans le fichier flux.json."
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

echo "Traitement terminé pour l'URL correspondant à l'indice $index."
