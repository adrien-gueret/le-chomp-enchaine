<?php
	class Controller_admin_categories_edit extends Controller_admin_categories
	{
		public function get_index($id = null)
		{
			$can_manage_categories = $this->_currentUser->hasPermission(Model_Groups::PERM_MANAGE_CATEGORIES);

			if ( ! $can_manage_categories) {
				$this->response->error('Vous ne pouvez pas modifier cette catégorie.', 403);
				return;
			}

			$category = Model_Categories::getById($id);

			if (empty($category)) {
				$this->response
						->status(404)
						->redirectToFullErrorPage(false)
						->set(\Eliya\Tpl::get('admin/categories/edit/not_found'));
				return;
			}

			$tpl_form	=	\Eliya\Tpl::get('admin/categories/form', [
				'edit_mode' 			=> true,
				'category_name'			=>	$category->prop('name'),
				'category_description'	=>	$category->prop('description'),
				'category_picture'		=>	$category->getMainPictureURL(),
				'end_action_url' 		=> 'edit?id='.$id
			]);

			$this->response->set(\Eliya\Tpl::get('admin/categories/edit/index', [
				'tpl_form'	=>	$tpl_form
			]));
		}

		public function put_index($id, $name, $description, $base64img = null)
		{
			// First, get category to update
			$category = Model_Categories::getById($id);

			// Update main picture
			$category->updateMainPicture($base64img, false);

			// Update category properties
			$category->setProps([
				'name' => $name,
				'description' => $description,
			]);

			Model_Categories::update($category);

			$this->get_index($id);
		}
	}