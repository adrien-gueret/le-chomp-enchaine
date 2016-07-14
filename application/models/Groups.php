<?php
	class Model_Groups extends EntityPHP\Entity
	{
		protected static $table_name = 'groups';

		protected $group_name;
		protected $can_manage_categories = 0;
		protected $can_manage_users = 0;
		protected $can_write_articles = 0;
		protected $can_edit_other_articles = 0;
		protected $can_publish_other_articles = 0;
		protected $can_read_unpublished_articles = 0;

		const	PERM_MANAGE_CATEGORIES = 'can_manage_categories',
				PERM_MANAGE_USERS = 'can_manage_users',
				PERM_WRITE_ARTICLES = 'can_write_articles',
				PERM_EDIT_OTHER_ARTICLES = 'can_edit_other_articles',
				PERM_PUBLISH_OTHER_ARTICLES = 'can_publish_other_articles',
				PERM_READ_UNPUBLISHED_ARTICLES = 'can_read_unpublished_articles';

		public static function __structure()
		{
			$thisClass = new ReflectionClass('Model_Groups');
			$constants = $thisClass->getConstants();

			$structure = ['group_name' => 'VARCHAR(255)'];

			foreach($constants as $constant) {
				$structure[$constant] = 'TINYINT(1)';
			}

			return $structure;
		}

		public function hasPermission($permission_name) {
			$permission_value = isset($this->$permission_name) ? $this->$permission_name : 0;

			return $permission_value == 1;
		}
	}
