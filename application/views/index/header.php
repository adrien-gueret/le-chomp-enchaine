<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<title><?= $view->page_title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?= $view->page_description; ?>" />
	<link rel="stylesheet" type="text/css" href="<?= Library_Assets::get('css/style.css'); ?>" />
	<link rel="shortcut icon" type="image/x-icon" href="<?= Library_Assets::get('favicon.ico'); ?>" />
	<?php if(isset($view->canonical_url)): ?>
		<link rel="canonical" href="<?= $view->canonical_url; ?>" />
	<?php endif; ?>
	<?php if(isset($view->facebook_meta_og)): ?>
		<?= $view->facebook_meta_og; ?>
	<?php endif; ?>
	<?php include('application/views/common_js.php'); ?>
</head>
<body>
	<header>
		<a id="logo" href="<?= $view->base_url; ?>">Le Chomp Enchaîné</a>
		<nav>
			<ul>
				<li>
					<a href="<?= $view->base_url; ?>newspapers">Journaux</a>
				</li>
				<li>
					<a href="#">Rubriques</a>
				</li>
				<li>
					<a href="#">A propos</a>
				</li>
				<?php if($view->currentUser->isConnected()): ?>
					<li class="logout_link">
						<a href="<?= $view->base_url; ?>admin/login/out">Se déconnecter</a>
					</li>
				<?php endif; ?>
			</ul>
		</nav>
	</header>

	<main>