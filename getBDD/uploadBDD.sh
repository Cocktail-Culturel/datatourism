#!/bin/bash

# # Lecture du fichier flux.json
# data=$(cat flux.json)

# # Déclaration d'un tableau pour stocker les URLs modifiées
# modified_urls=()

# # Extraction des URLs et ajout dans le tableau
# urls=($(echo "$data" | jq -r '.[]'))
# for url in "${urls[@]}"
# do
#     modified_url="$url"bb3e487f-7b9f-4085-b409-a530cbe2fb90
#     echo "Téléchargement de $modified_url ..."
#     wget "$modified_url" -O flux.gz
    
#     echo "Décompression de flux.gz ..."
#     gzip -d flux.gz
    
#     echo "Renommage du fichier décompressé en flux.rdf ..."
#     mv flux flux.rdf
    
#     echo "Envoi de flux.rdf vers http://localhost:9999/blazegraph/namespace/kb/sparql ..."
#     curl -X POST -H "Content-Type:application/rdf+xml" --data-binary @flux.rdf "https://datatourism-bdd.cocktail-culturel.com/blazegraph/namespace/kb/sparql"
    
#     echo "Traitement terminé pour $modified_url"
#     echo
# done

# Vérification si l'URL est passée en tant que paramètre
if [[ -z $1 ]]; then
    echo "Veuillez fournir l'URL en tant que paramètre."
    exit 1
fi

url=$1
modified_url="$url"bb3e487f-7b9f-4085-b409-a530cbe2fb90

echo "Téléchargement de $modified_url ..."
wget "$modified_url" -O flux.gz

echo "Décompression de flux.gz ..."
gzip -d flux.gz

echo "Renommage du fichier décompressé en flux.rdf ..."
mv flux flux.rdf

echo "Envoi de flux.rdf vers https://datatourism-bdd.cocktail-culturel.com/blazegraph/namespace/kb/sparql ..."
curl -X POST -H "Content-Type:application/rdf+xml" --data-binary @flux.rdf "https://datatourism-bdd.cocktail-culturel.com/blazegraph/namespace/kb/sparql"

echo "Traitement terminé."
