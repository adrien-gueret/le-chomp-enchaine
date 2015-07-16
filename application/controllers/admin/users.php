<?php
	class Controller_admin_users extends Controller_admin
	{
		protected static $permissions_required = [
			Model_Groups::PERM_MANAGE_USERS
		];

		public function get_index()
		{
			$tpl_users	=	null;
			$all_users = Model_Users::getAllWithGroupsInfos();

			if(empty($all_users))
				$tpl_users	=	\Eliya\Tpl::get('admin/users/none');
			else {
				$tpl_users	=	\Eliya\Tpl::get('admin/users/list', [
					'all_users'	=>	$all_users
				]);
			}

			$this->response->set(\Eliya\Tpl::get('admin/users/index', [
				'tpl_users' => $tpl_users,
				'groups' => Model_Groups::getAll(),
			]));
		}

		public function post_index($username, $email, $id_group)
		{
			$password = Library_String::generatePassword();
			$group = Model_Groups::getById($id_group);

			Model_Users::add(new Model_Users($username, $email, $password, $group));

			// TODO: send an email with generated password to new user
			$this->get_index();
		}
	}