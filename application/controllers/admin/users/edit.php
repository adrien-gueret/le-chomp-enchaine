<?php
class Controller_admin_users_edit extends Controller_admin_users
{
	public function get_index($id = null)
	{
		$user = Model_Users::getById($id);

		if( ! $user)
		{
			$this->response->error("L'utilisateur demandé n'existe pas !", 404);
			return;
		}

		$this->response->set(\Eliya\Tpl::get('admin/users/edit/index', [
			'user' => $user,
			'usergroup' => $user->load('usergroup'),
			'groups' => Model_Groups::getAll(),
		]));
	}

	public function put_index($id, $username, $email, $password = null, $id_group = null)
	{
		$user = Model_Users::getById($id);

		$propsUpdate = [
			'username' => $username,
			'email' => $email
		];

		if($id_group !== null && ! empty($id_group))
		{
			$group = Model_Groups::getById($id_group);
			$propsUpdate['usergroup'] = $group;
		}
		else
		{
			$user->load('usergroup');
		}

		if($password !== null && ! empty($password))
		{
			$propsUpdate['password'] = Library_String::hash(trim($password));
		}

		$user->setProps($propsUpdate);

		Model_Users::update($user);

		$this->response->redirect('../users', 200);
	}
}
?>