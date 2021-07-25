# Book library with Symfony, React and Elasticsearch

## Lancer le projet en local
  
Pour lancer le projet, avec `docker` installé sur l'OS de votre choix, à la racine du projet,
lancez la commande :

```shell
docker-compose up
```

(ou `docker-compose up -d` pour le mode détaché)

> Lors du premier lancement, docker construira l'image présente dans `Dockerfile`, 
> cela prendra un peu plus de temps mais ce n'est qu'une fois.

Dans un autre terminal, si vous souhaitez accéder a l'image, toujours à la racine, lancez :

```shell
docker-compose run --rm book-library bash
```

Ceci ouvrira le terminal à l'intérieur du conteneur ``, à la racine du projet.


### Services docker
Les services `docker` sont configurés ainsi:
- Service `book-library` : http://localhost:2000
- Service `kibana`:  http://localhost:5601

### Composer
Une fois le projet lancé en local, il faudra installer les dépendances composer :

```shell
composer install
```

### Elasticsearch
Pour charger la base de données Elasticsearch effectuer la commande suivante :

```shell
php bin/console elasticsearch:import-books
```

Cette commande peut être automatisé par un cron pour mettre à jour 
la base de données de façon fréquente si le fichier source csv vient à être modifié.