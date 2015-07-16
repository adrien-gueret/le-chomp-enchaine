<?php
	class Controller_admin_sections extends Controller_admin
	{
		protected static $permissions_required = [
			Model_Groups::PERM_MANAGE_SECTIONS
		];

		public function get_index()
		{
			$tpl_sections	=	null;
			$all_sections = Model_Sections::getAll();

			if($all_sections->isEmpty())
				$tpl_sections	=	\Eliya\Tpl::get('admin/sections/none');
			else {
				$tpl_sections	=	\Eliya\Tpl::get('admin/sections/list', [
					'all_sections'	=>	$all_sections
				]);
			}

			$this->response->set(\Eliya\Tpl::get('admin/sections/index', [
				'tpl_sections' => $tpl_sections
			]));
		}

		public function post_index($name)
		{
			Model_Sections::add(new Model_Sections($name));
			$this->get_index();
		}
	}