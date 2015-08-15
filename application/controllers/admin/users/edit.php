<?php
class Controller_admin_users_edit extends Controller_admin
{
	public function get_index($id = null)
	{
		// Force to edit the current user if they don't have the proper permissions
		if( ! $this->_currentUser->hasPermission(Model_Groups::PERM_MANAGE_USERS))
		{
			$id = $this->_currentUser->getId();
		}
		else
		{
			$groups = Model_Groups::getAll();
		}

		$user = Model_Users::getById($id);

		if( ! $user)
		{
			$this->response->error("L'utilisateur demandé n'existe pas !", 404);
			return;
		}

		$this->response->set(\Eliya\Tpl::get('admin/users/edit/index', [
			'edit_current' => $id == $this->_currentUser->getId(),
			'user' => $user,
			'usergroup' => $user->load('usergroup'),
			'groups' => isset($groups) ? $groups : null,
		]));
	}

	public function get_profil()
	{
		$this->get_index($this->_currentUser->getId());
	}

	public function put_index($id, $username, $email, $password = null, $id_group = null)
	{
		// Force to edit the current user if they don't have the proper permissions
		if( ! $this->_currentUser->hasPermission(Model_Groups::PERM_MANAGE_USERS))
		{
			$id = $this->_currentUser->getId();
		}

		$user = Model_Users::getById($id);

		$propsUpdate = [
			'username' => $username,
			'email' => $email
		];

		if($id_group !== null && ! empty($id_group) && $this->_currentUser->hasPermission(Model_Groups::PERM_MANAGE_USERS))
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

		// Disconnect the user if they changed their own profile
		if($id === $this->_currentUser->getId())
			$this->response->redirect('../login/out', 200);
		else
			$this->response->redirect('../users', 200);
	}
}
?>