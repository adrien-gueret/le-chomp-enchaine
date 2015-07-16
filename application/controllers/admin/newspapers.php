<?php
	class Controller_admin_newspapers extends Controller_admin
	{
		protected static $permissions_required = [
			Model_Groups::PERM_MANAGE_NEWSPAPERS
		];

		public function get_index()
		{
			$tpl_newspapers	=	null;
			$all_newspapers = Model_Newspapers::getAll();

			if($all_newspapers->isEmpty())
				$tpl_newspapers	=	\Eliya\Tpl::get('admin/newspapers/none');
			else {
				$tpl_newspapers	=	\Eliya\Tpl::get('admin/newspapers/list', [
					'all_newspapers'	=>	$all_newspapers
				]);
			}

			$this->response->set(\Eliya\Tpl::get('admin/newspapers/index', [
				'tpl_newspapers' => $tpl_newspapers
			]));
		}

		public function post_index($name)
		{
			Model_Newspapers::add(new Model_Newspapers($name));
			$this->get_index();
		}
	}