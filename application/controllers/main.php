<?php
	abstract class Controller_main extends Eliya\Controller
	{
		protected $_currentUser = null;

		public function __init()
		{
			$this->_currentUser = Model_Users::getCurrentUser();
			\Eliya\Tpl::set('currentUser', $this->_currentUser);
		}
	}