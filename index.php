<?php

	//Require framework core
	require_once 'system/Core.class.php';

	//Init core
	\Eliya\Core::init();

    // Set session time (24H)
    session_set_cookie_params(86400);
    ini_set('session.gc_maxlifetime', 86400);

	//Activate the session
	session_start();

	//Init DB
	require_once 'application/vendors/EntityPHP/src/EntityPHP.php';

	$sql	=	\Eliya\Config('main')->SQL;

	//Handle received request
	$request		=	new \Eliya\Request($_SERVER['REQUEST_URI']);
	$current_url	=	$request->getProtocol().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	define('BASE_URL', $request->getBaseURL());
	define('STATIC_URL', \Eliya\Config('main')->STATIC_URL);
	define('PUBLIC_FOLDER_PATH', __DIR__.DIRECTORY_SEPARATOR .'public'.DIRECTORY_SEPARATOR );

	if(substr($current_url, -1) !== '/')
		$current_url	.=	'/';

	$page_description = 'Un site réalisé par des fans pour les fans de Nintendo. ';
	$page_description .= 'Retrouvez des articles en rapport avec Nintendo, que ce soit sur ';
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

	if($response->isError())
		error_log("Error - " . $response->status() . " - Request: ". $_SERVER['REQUEST_URI'], 4);
