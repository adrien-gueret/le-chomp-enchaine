<?php
	class Model_Users extends EntityPHP\Entity
	{
		protected static $table_name = 'users';

		protected $username;
		protected $email;
		protected $password;
		protected $usergroup;

		public static function __structure()
		{
			return [
				'username' => 'VARCHAR(255)',
				'email' => 'VARCHAR(254)',
				'password' => 'CHAR(40)',
				'usergroup' => 'Model_Groups'
			];
		}

		public function __construct($username = null, $email = null, $password = null, Model_Groups $group = null)
		{
			$this->username = $username;
			$this->email = $email;
			$this->password = empty($password) ? null : Library_String::hash(trim($password));
			$this->usergroup = $group;
		}

		public static function login($email, $password)
		{
			$password	=	Library_String::hash($password);

			$user	=	 Model_Users::createRequest()
							->where('email=? AND password=?', [$email, $password])
							->getOnly(1)
							->exec();

			if( ! empty($user))
				Library_Session::set('currentUser', serialize($user));

			return $user;
		}

		public static function logout()
		{
			Library_Session::remove('currentUser');
		}

		public static function getCurrentUser()
		{
			$user	=	Library_Session::get('currentUser');
			return empty($user) ?  new Model_Users() : unserialize($user);
		}

		public static function getAllWithGroupsInfos()
		{
			return self::createRequest()
						->select('username, email, usergroup.group_name')
						->exec();
		}

		public function isConnected()
		{
			return $this->getId() > 0;
		}

		public function getArticles()
		{
			return Model_Articles::createRequest()
				->where('author.id = ?', [$this->getId()])
				->exec();
		}

		public function hasPermission($permission_name)
		{
			return $this->load('usergroup')->hasPermission($permission_name);
		}
	}
