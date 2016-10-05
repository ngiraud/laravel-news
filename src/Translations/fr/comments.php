<?php
return [
	'author'        => 'Auteur',
	'title'         => '{0,1} <span class="comments_counter">:nb</span> commentaire sur cet article|[2,Inf] <span class="comments_counter">:nb</span> commentaires sur cet article',
	'title-reply'   => 'Répondre à',
	'title-add'     => 'Laisser un commentaire',
	'flash'         => [
		'created'    => [
			'ok'    => "Le commentaire a bien été ajouté.",
			'error' => "Une erreur s'est produite.",
		],
		'approve'    => [
			'ok'    => "Le commentaire a bien été approuvé.",
			'error' => "Une erreur s'est produite.",
		],
		'disapprove' => [
			'ok'    => "Le commentaire a bien été désapprouvé.",
			'error' => "Une erreur s'est produite.",
		],
	],
	'btn'           => [
		'add'        => 'Laisser un commentaire',
		'approve'    => 'Approuver',
		'disapprove' => 'Désapprouver',
		'reply'      => 'Répondre',
		'cancel'     => 'Annuler',
		'OK'         => 'OK',
	],
	'notifications' => [
		'admin' => [
			'subject' => "Un nouveau commentaire a été publié !",
			'added' => "Un commentaire a été publié pour l'actualité \":news_title\".",
			'author'   => 'Auteur : :author_name <:author_email>',
			'date'   => 'Date : :date',
			'content'   => 'Contenu :',
		],
		'front' => [
			'subject' => "Une actualité à laquelle vous avez participé a été commentée !",
			'added' => "Un commentaire a été publié pour l'actualité \":news_title\".",
			'author'   => 'Auteur : :author_name <:author_email>',
			'date'   => 'Date : :date',
			'content'   => 'Contenu :',
		],
		'btn'   => [
			'moderate'  => "Modérer le comentaire",
			'go'  => "Voir le commentaire",
		]
	],
];
