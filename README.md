# Drupal 8 Ready

Ce dépôt a été créé pour mettre à disposition un site Drupal 8 prêt à l'emploi.

## Installation et utilisation

Ce Drupal est prêt à l'emploi. Il vous suffit de le cloner sur votre PC et de le démarrer.

### Prérequis

- PHP : **v7.3** (ou ultérieur)

### Informations

- Drupal : **8.9.7**
- Modules : **rendez-vous plus bas, section modules**

### Installation & Démarrage

```
composer update && php -S localhost:8001
```

> **NB :** Le noyau de Drupal doit être la version 8.9.7.
> 
> Si cela n'est pas le cas, voici la commande qui permet de forcer le choix de noyau drupal : 
> ```shell
> composer require drupal/core-recommended:8.9.7 drupal/core-composer-scaffold:8.9.7 drupal/core-project-message:8.9.7 --update-with-all-dependencies
> ```

###  Identifiants

- login : dev
- mdp : dev

## Les modules

Dans Drupal, il est possible de développer et d'ajouter des modules.
<br/>Vous y trouver quelques modèles intégrer à ce projet dans le dossier [/modules/](/modules/) incluant un guide [REAMDME.md](/modules/README.md)

## Help

En cas de problème, vous trouverez une documentation ici : [2023-esiee-projectlab/drupal_help](https://github.com/2023-esiee-projectlab/drupal_help)
