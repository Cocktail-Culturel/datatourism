#!/bin/bash

# Lecture du fichier flux.json
data=$(cat flux.json)

# Extraction des URLs et stockage dans des variables
Val_de_Loire_Corse_IDF_DOM_TOM=$(echo "$data" | jq -r '."Val-de-Loire+Corse+IDF+DOM/TOM"')
Nouvelle_Aquitaine=$(echo "$data" | jq -r '."Nouvelle_Aquitaine"')
Occitanie=$(echo "$data" | jq -r '."Occitanie"')
Normandie=$(echo "$data" | jq -r '."Normandie"')
AURA=$(echo "$data" | jq -r '."AURA"')
Pays_de_la_Loire_PACA=$(echo "$data" | jq -r '."Pays_de_la_Loire+PACA"')
Haut_De_France=$(echo "$data" | jq -r '."Haut_De_France"')
Bretagne=$(echo "$data" | jq -r '."Bretagne"')
Grand_Est=$(echo "$data" | jq -r '."Grand-Est"')
BFC=$(echo "$data" | jq -r '."BFC"')

# Affichage des variables
echo "Val_de_Loire_Corse_IDF_DOM_TOM: $Val_de_Loire_Corse_IDF_DOM_TOM"
echo "Nouvelle_Aquitaine: $Nouvelle_Aquitaine"
echo "Occitanie: $Occitanie"
echo "Normandie: $Normandie"
echo "AURA: $AURA"
echo "Pays_de_la_Loire_PACA: $Pays_de_la_Loire_PACA"
echo "Haut_De_France: $Haut_De_France"
echo "Bretagne: $Bretagne"
echo "Grand_Est: $Grand_Est"
echo "BFC: $BFC"
