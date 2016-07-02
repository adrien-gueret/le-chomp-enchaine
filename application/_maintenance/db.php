<?php
	// Require framework core
	require_once '../../system/Core.class.php';

	// Init core
	\Eliya\Core::init();

	// Init DB
	require_once '../vendors/EntityPHP/src/EntityPHP.php';

	$sql	=	\Eliya\Config('main')->SQL;

	\EntityPHP\Core::connectToDB($sql['HOST'], $sql['USER'], $sql['PASSWORD'], $sql['DATABASE']);

	// Include all models files
	\Eliya\Core::requireDirContent('../models');

	// Generate the dabatase based on our models
	\EntityPHP\Core::generateDatabase();

	Model_Sections::add(new Model_Sections('Edito'));
	Model_Sections::add(new Model_Sections('Quoi d\'neuf Mario ?'));
	Model_Sections::add(new Model_Sections('Analyse de personnage'));
	Model_Sections::add(new Model_Sections('L\'entreprise Nintendo'));
	Model_Sections::add(new Model_Sections('Découverte de jeu'));
	Model_Sections::add(new Model_Sections('Anthologie musicale'));
	Model_Sections::add(new Model_Sections('Produits dérivés'));

	Model_Groups::add(new Model_Groups([
		'group_name' => 'Anonymes',
		'can_manage_sections' => 0,
		'can_manage_users' => 0,
		'can_write_articles' => 0,
		'can_edit_other_articles' => 0,
		'can_read_unpublished_articles' => 0,
	]));

	$group_redactors = Model_Groups::add(new Model_Groups([
		'group_name' => 'Rédacteurs',
		'can_manage_sections' => 0,
		'can_manage_users' => 0,
		'can_write_articles' => 1,
		'can_edit_other_articles' => 0,
		'can_read_unpublished_articles' => 0,
	]));

	Model_Groups::add(new Model_Groups([
		'group_name' => 'Rédacteurs en chef',
		'can_manage_sections' => 1,
		'can_manage_users' => 0,
		'can_write_articles' => 1,
		'can_edit_other_articles' => 0,
		'can_read_unpublished_articles' => 1,
	]));

	$group_admin = Model_Groups::add(new Model_Groups([
		'group_name' => 'Admins',
		'can_manage_sections' => 1,
		'can_manage_users' => 1,
		'can_write_articles' => 1,
		'can_edit_other_articles' => 1,
		'can_read_unpublished_articles' => 1,
	]));