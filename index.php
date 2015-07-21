<?php

	//Require framework core
	require_once 'system/Core.class.php';

	//Init core
	\Eliya\Core::init();

	//Activate the session
	session_start();

	//Init DB
	require_once 'application/vendors/EntityPHP/EntityPHP.php';

	$sql	=	\Eliya\Config('main')->SQL;

	//Handle received request
	$request		=	new \Eliya\Request($_SERVER['REQUEST_URI']);
	$current_url	=	$request->getProtocol().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	define('BASE_URL', $request->getBaseURL());
	define('STATIC_URL', \Eliya\Config('main')->STATIC_URL);
	define('PUBLIC_FOLDER_PATH', __DIR__.DIRECTORY_SEPARATOR .'public'.DIRECTORY_SEPARATOR );

	if(substr($current_url, -1) !== '/')
		$current_url	.=	'/';

	$page_description = 'Un fanzine presque-mensuel réalisé par des fans pour les fans de Nintendo. ';
	$page_description .= 'Chaque mois (à peu prêt !), retrouvez des articles en rapport avec Nintendo, que ce soit sur ';
	$page_description .= 'l\'actualité, des découvertes de jeux ou des dossiers sur divers sujets.';

	\Eliya\Tpl::set([
		'page_title'				=>	'Le Chomp Enchainé',
		'page_description'			=>	$page_description,
		'base_url'					=>	BASE_URL,
		'static_url'				=>	STATIC_URL,
		'current_url'				=>	$current_url,
	]);

	$response	=	$request->response();

	try
	{
		if( ! empty($sql))
			\EntityPHP\Core::connectToDB($sql['HOST'], $sql['USER'], $sql['PASSWORD'], $sql['DATABASE']);
		else
			throw new Exception('Impossible de se connecter à la base de données');

		$request->exec();
	}
	catch(Exception $e)
	{
		ob_clean();
		$response->set(null)->error($e->getMessage(), 500);
	}

	$response->render();