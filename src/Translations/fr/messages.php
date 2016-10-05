<?php
return [
	'news_title' => 'Actualités',
	'title'      => [
		'add'  => "Création d'une actualité",
		'edit' => 'Edition de l\'actualité :title',
		'show' => ':title',
		'list' => 'Liste des actualités',
	],
	'edit'       => [
		'tooltip' => 'Editer',
	],
	'deleted'    => [
		'confirm' => "Supprimer cette actualité ?",
		'tooltip' => 'Supprimer',
	],
	'menu'       => [
		'title' => [
			'add'  => "Créer une actualité",
			'edit' => ':title',
			'show' => 'Afficher l\'actualité :title',
			'list' => 'Liste des actualités',
		],
	],
	'table'      => [
		'published_date' => trans('validation.attributes.published_at'),
		'published'      => 'Publié',
		'title'          => 'Titre',
		'excerpt'        => 'Extrait',
	],
	'flash'      => [
		'created' => "Cette actualité a bien été ajoutée !",
		'updated' => 'Cette actualité a bien été modifiée !',
		'deleted' => [
			'ok'    => "L'actualité a bien été supprimée.",
			'error' => "Une erreur s'est produite pendant la suppression de l'actualité.",
		],
		'published'  => [
			'ok'    => "L'actualité a bien été publiée.",
			'error' => "Une erreur s'est produite pendant la publication de l'actualité.",
		],
	],
];