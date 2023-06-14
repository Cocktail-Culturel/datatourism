# DataTourisme

0. Prérequis : docker, docker compose et .env

1. Télécharger le flux de données depuis [datatourisme](https://diffuseur.datatourisme.fr/fr/login) et placer le dans un nouveau répertoire `/dataset/kb/data/flux.rdf`

2. Lancer l'api et la bdd avec `docker compose up -d` puis charger la bdd dans blazegraph

3. Exemples d'utilisation [ici](https://datatourisme.frama.io/api/#/README)
