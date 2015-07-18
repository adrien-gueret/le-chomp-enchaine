<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<title>Administration &bull; <?= $view->page_title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="<?= $view->base_url; ?>public/css/style.css" />
	<link rel="shortcut icon" type="image/x-icon" href="<?= $view->base_url; ?>public/favicon.ico" />
	<?php include('/application/views/common_js.php'); ?>
	<script src="<?= $view->base_url; ?>public/js/angular/file-reader/file-reader.js"></script>
</head>
<body>
	<header>
		<a id="logo" href="<?= $view->base_url; ?>">Le Chomp Enchaîné - Admin</a>
		<nav>
			<ul>
				<?php if($view->currentUser->isConnected()): ?>
					<?php if($view->currentUser->hasPermission(Model_Groups::PERM_MANAGE_NEWSPAPERS)): ?>
						<li>
							<a href="<?= $view->base_url; ?>admin/newspapers">Fanzines</a>
						</li>
					<?php endif; ?>
					<?php if($view->currentUser->hasPermission(Model_Groups::PERM_WRITE_ARTICLES)): ?>
						<li>
							<a href="<?= $view->base_url; ?>admin/articles">Articles</a>
						</li>
					<?php endif; ?>
					<?php if($view->currentUser->hasPermission(Model_Groups::PERM_MANAGE_SECTIONS)): ?>
						<li>
							<a href="<?= $view->base_url; ?>admin/sections">Rubriques</a>
						</li>
					<?php endif; ?>
					<?php if($view->currentUser->hasPermission(Model_Groups::PERM_MANAGE_USERS)): ?>
						<li>
							<a href="<?= $view->base_url; ?>admin/users">Utilisateurs</a>
						</li>
					<?php endif; ?>
					<li class="logout_link">
						<a href="<?= $view->base_url; ?>admin/login/out">Se déconnecter</a>
					</li>
				<?php endif; ?>
				<li>
					<a href="<?= $view->base_url; ?>">Retour au site</a>
				</li>
			</ul>
		</nav>
	</header>
	<main>
