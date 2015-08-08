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

			$email_content = \Eliya\Tpl::get('emails/register', [
				'username'	=>	$username,
				'email'		=>	$email,
				'password'	=>	$password,
				'login_url'	=>	BASE_URL.'admin/login',
			]);

			Library_Email::send($email, 'Bienvenue sur Le Chomp Enchaîné !', $email_content);
			$this->get_index();
		}

		public function delete_index($id_user)
		{
			if($this->_currentUser->getId() === $id_user)
			{
				$this->response->error('Vous ne pouvez pas vous supprimer vous-même !', 403);
				return;
			}

			$user = Model_Users::getById($id_user);

			if($user)
				$user->remove();

			$this->get_index();
		}
	}