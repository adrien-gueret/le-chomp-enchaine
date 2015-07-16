<?php
	abstract class Controller_admin extends Controller_main
	{
		protected $_header_view_path = 'admin';
		protected static $permissions_required = [];

		public function __before($checkConnection = true)
		{
			if ($checkConnection && ! $this->_currentUser->isConnected()) {
				$this->response->error('Vous devez être connecté pour effectuer cette action.', 401);
				return false;
			}

			foreach(static::$permissions_required as $permission) {
				if ( ! $this->_currentUser->hasPermission($permission)) {
					$this->response->error('Vous n\'avez pas les droits nécessaires pour effectuer cette action.', 403);
					return false;
				}
			}
		}
	}