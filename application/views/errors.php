<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<title>Erreur <?= $view->error_number; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" type="image/x-icon" href="<?= Library_Assets::get('favicon.ico'); ?>" />
		<link rel="stylesheet" type="text/css" href="<?= Library_Assets::get('css/errors.css'); ?>" />
	</head>
	<body>
        <h1>Erreur #<?= $view->error_number; ?> !</h1>
		<div id="scene" class="background-animation">
			<div id="chomp-container">
				<div class="chomp" id="chomp"></div>
				<p id="bubble"><?= $view->message; ?></p>
			</div>
			<div id="towers" class="background-animation"></div>
			<div id="ground" class="background-animation"></div>
		</div>
		<p>
			<a href="<?= $view->base_url; ?>">Retour sur le site</a>
		</p>
	<script>
		function clickHandler() {
			document.getElementById('scene').className += ' clicked';
			document.getElementById('bubble').innerHTML = 'Aaargh !';
			this.removeEventListener('click', clickHandler);
		}
		document.getElementById('chomp').addEventListener('click', clickHandler);
	</script>
	</body>
</html>
