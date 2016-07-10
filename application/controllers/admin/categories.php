<?php
	class Controller_admin_categories extends Controller_admin
	{
		protected static $permissions_required = [
			Model_Groups::PERM_MANAGE_CATEGORIES
		];

		public function get_index()
		{
			$tpl_categories	=	null;
			$all_categories = Model_Categories::getAll();

			if($all_categories->isEmpty())
				$tpl_categories	=	\Eliya\Tpl::get('admin/categories/none');
			else {
				$tpl_categories	=	\Eliya\Tpl::get('admin/categories/list', [
					'all_categories'	=>	$all_categories
				]);
			}

			$this->response->set(\Eliya\Tpl::get('admin/categories/index', [
				'tpl_categories' => $tpl_categories
			]));
		}

		public function post_index($name, $base64img)
		{
			$category = Model_Categories::add(new Model_Categories($name));
			$category->updateMainPicture($base64img);
			$this->get_index();
		}
	}