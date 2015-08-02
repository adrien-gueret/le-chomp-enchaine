<?php
	class Controller_admin_articles_edit extends Controller_admin_articles
	{
		public function get_index($id = null)
		{
			$article = Model_Articles::getById($id);

			if (empty($article)) {
				$this->response
						->status(404)
						->redirectToFullErrorPage(false)
						->set(\Eliya\Tpl::get('admin/articles/edit/not_found'));
				return;
			}

			$author_is_current_user = $article->load('author')->equals($this->_currentUser);
			$can_edit_other_articles = $this->_currentUser->hasPermission(Model_Groups::PERM_EDIT_OTHER_ARTICLES);

			if ( ! $author_is_current_user && ! $can_edit_other_articles) {
				$this->response->error('Vous ne pouvez pas modifier cet article.', 403);
				return;
			}

			$article->fileSrc = $article->getMainPictureURL();

			$this->response->set(\Eliya\Tpl::get('admin/articles/edit/index', [
				'article'		=>	$article,
				'section'		=>	$article->load('section'),
				'all_sections'	=>	Model_Sections::getAll()
			]));
		}

		public function put_index($id, $title, $introduction, $content, $id_section, $base64img = null)
		{
			$article = Model_Articles::getById($id);

			$article->updateMainPicture($base64img);

			$article->setProps([
				'title'				=>	$title,
				'introduction'		=>	$introduction,
				'content'			=>	$content,
				'section'			=>	Model_Sections::getById($id_section),
				'date_last_update'	=> $_SERVER['REQUEST_TIME']
			]);

			$article->load('author');
			$article->load('newspaper');

			Model_Articles::update($article);

			$this->get_index($id);
		}
	}