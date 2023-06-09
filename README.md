# DataTourisme

0. Prérequis : Java 9+, Php 7+, cURL et wget

1. Télécharger le flux de données depuis [datatourisme](https://diffuseur.datatourisme.fr/fr/login)

2. Télécharger la base de données [graphQL](https://github.com/blazegraph/database/releases/download/BLAZEGRAPH_2_1_6_RC/blazegraph.jar) ou

   ```bash
   wget https://github.com/blazegraph/database/releases/download/BLAZEGRAPH_2_1_6_RC/blazegraph.jar
   ```

3. Lancer la base de données via `java -jar blazegraph.jar`. La BDD est accessible via l'interface web [http://localhost:9999/](http://localhost:9999/)

4. Importer le flux de données via `curl -X POST -H "Content-Type:application/rdf+xml" --data-binary @flux.rdf "http://localhost:9999/blazegraph/namespace/kb/sparql"`

5. `composer install`

6. Tester l'api avec _test.php_

7. Exemples d'utilisation [ici](https://datatourisme.frama.io/api/#/README)
