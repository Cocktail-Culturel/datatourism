# Gestion de la base de donnée
## Principe général
- La base de donnée s'update via le script `uploadBDD.sh` qui télécharge les fichiers de flux indiqués dans `flux.json` et les charges dans la BDD.
## Modification de flux.json
1. Récupérer le lien vers le flux sur l'espace diffuseur de Datatourism : https://diffuseur.datatourisme.fr/fr/flux 
2. Se connecter au VPS
3. Dans le VPS se rendre dans le dossier getBDD : <pre>cd /home/ubuntu/datatourism/getBDD</pre>
4. Modifier le fichier flux.json : <pre>nano flux.json</pre> en ajoutant le lien vers le flux voulu et en respectant le format du fichier.
5. Lancer le script : <pre>bash uploadBDD</pre>

**Attention :** Les flux doivent être configurés pour fournir des données au format RDF-XML.

**Note** Si un flux est supprimé du flux.json alors les données contenu en BDD liés à ce flux ne seront plus mis à jour mais resteront présentes. Si on souhaite les supprimer (afin de gagner en performance) il est nécessaire de relancer une image docker de la BDD puis ensuite de lancer le script `uploadBDD.sh`

## Automatisation 
- Le script `uploadBDD.sh` est programmé via crontab pour se lancer tout les jours à 4h afin de mettre à jour la BDD.