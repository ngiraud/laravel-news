# Package News pour Laravel 5.3 #

Ce package a été conçu pour être utilisé avec l'interface d'administration.

### Installation ###

* Ajouter les lignes dans le composer.json

```shell
"require": {
  ...
  "ngiraud1/news": "dev-master",
  ...
},
"repositories": [{
  "type": "vcs",
  "url": "https://github.com/ngiraud/laravel-news"
}],
```
et faire la commande :
```shell
composer update
```

* Ajouter le ServiceProvider dans app.php

```php
NGiraud\News\NewsServiceProvider::class,
```

* Publier les vues, traductions et assets

```shell
php artisan vendor:publish --provider="NGiraud\News\NewsServiceProvider" --tag=news
```

* Publier les plugins (Pickadate) si besoin

```shell
php artisan vendor:publish --provider="NGiraud\News\NewsServiceProvider" --tag=news_plugins
```

* Migrer les tables de news

```shell
php artisan migrate
```

* Le menu pour les news peut être ajouté grâce à l'inclusion suivante
```php
@include('news::admin.news.menu')
```

* Le style peut être ajouté dans admin.scss avec la ligne suivante (lancer gulp pour ajouter le css)
```scss
@import "../../vendor/news/sass/admin/comment";
```