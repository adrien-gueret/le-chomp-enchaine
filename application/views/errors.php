<!--
	This is a common and simple whole view (including header & footer) for handling errors.
	This view is used by Error_X classes (from ./application/errors/)
-->
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<title>Error <?= $view->error_number; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" type="image/x-icon" href="./public/img/favicon.ico" />
	</head>
	<body>
        <h1>Error #<?= $view->error_number; ?>!</h1>
		<p><?= $view->message; ?></p>
	</body>
</html>