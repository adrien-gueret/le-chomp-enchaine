<?php
	class Controller_index extends Controller_main
	{
		public function get_index()
		{
			$password = Library_String::generatePassword();

			$perm = $this->_currentUser->hasPermission(Model_Groups::PERM_MANAGE_SECTIONS);
			$this->response->set($perm ? 'oui' : 'non');
		}
	}