<?php
	class Controller_admin_newspapers_edit extends Controller_admin_newspapers
	{
		public function get_index($id = null)
		{
			$newspaper = Model_Newspapers::getById($id);

			if (empty($newspaper)) {
				$this->response
						->status(404)
						->redirectToFullErrorPage(false)
						->set(\Eliya\Tpl::get('admin/newspapers/edit/not_found'));
				return;
			}

			$tpl_articles	=	null;
			$articles		=	$newspaper->load('articles');

			if ($articles->isEmpty()) {
				$tpl_articles	=	\Eliya\Tpl::get('admin/newspapers/edit/articles/none');
			} else {
				$tpl_articles	=	\Eliya\Tpl::get('admin/newspapers/edit/articles/list', [
					'articles'		=>	$articles,
					'id_newspaper'	=>	$id,
				]);
			}

			$this->response->set(\Eliya\Tpl::get('admin/newspapers/edit/index', [
				'newspaper'				=>	$newspaper,
				'unpublished_articles'	=>	Model_Articles::getUnpublished(),
				'tpl_articles'			=>	$tpl_articles,
			]));
		}

		public function put_index($id, $name, $isPublished = false)
		{
			$newspaper = Model_Newspapers::getById($id);

			$newspaper->setProps([
				'name'				=>	$name,
				'date_publication'	=>	$isPublished ? $_SERVER['REQUEST_TIME'] : null,
			]);

			Model_Newspapers::update($newspaper);

			$this->get_index($id);
		}

		public function put_addArticle($id, $id_article)
		{
			$newspaper	=	Model_Newspapers::getById($id);
			$article	=	Model_Articles::getById($id_article);

			$newspaper->load('articles')->push($article);
			Model_Newspapers::update($newspaper);
			$this->get_index($id);
		}

		public function put_removeArticle($id, $id_article)
		{
			$newspaper	=	Model_Newspapers::getById($id);
			$article	=	Model_Articles::getById($id_article);

			$newspaper->load('articles')->remove($article);
			Model_Newspapers::update($newspaper);
			$this->get_index($id);
		}
	}