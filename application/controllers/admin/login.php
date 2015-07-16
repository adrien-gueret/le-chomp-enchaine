<?php
	class Controller_admin_login extends Controller_admin
	{
		public function __before($checkConnection = false)
		{
			parent::__before($checkConnection);
		}

		public function get_index()
		{
			$this->response->set(Eliya\Tpl::get('admin/login'));
		}

		public function get_out()
		{
			Model_Users::logout();
			$this->_currentUser = new Model_Users();
			\Eliya\Tpl::set('currentUser', $this->_currentUser);
			$this->get_index();
		}

		public function post_index($email, $password)
		{
			$user	=	Model_Users::login($email, $password);

			if(empty($user)) {
				Eliya\Tpl::set('errorMessage', 'Vos identifiants sont incorrects.');
				$this->response->status(401)->redirectToFullErrorPage(false);
				$this->get_index();
				return;
			}

			$this->_currentUser	=	$user;
			\Eliya\Tpl::set('currentUser', $this->_currentUser);

			$this->response->redirect('articles', 200);
		}
	}