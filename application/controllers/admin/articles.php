<?php
	class Controller_admin_articles extends Controller_admin
	{
		protected static $permissions_required = [
			Model_Groups::PERM_WRITE_ARTICLES
		];

		public function get_index()
		{
			$tpl_articles	=	null;
			$my_articles = $this->_currentUser->getArticles();

			if($my_articles->isEmpty())
				$tpl_articles	=	\Eliya\Tpl::get('admin/articles/none');
			else {
				$tpl_articles	=	\Eliya\Tpl::get('admin/articles/list', [
					'articles'	=>	$my_articles
				]);
			}

			$this->response->set(\Eliya\Tpl::get('admin/articles/index', [
				'tpl_articles' => $tpl_articles,
				'sections'	=>	Model_Sections::getAll()
			]));
		}

		public function post_index($title, $id_section)
		{
			$newArticle = new Model_Articles([
				'title'		=>	$title,
				'section'	=>	Model_Sections::getById($id_section),
				'author'	=>	$this->_currentUser
			]);

			$newArticle	=	Model_Articles::add($newArticle);
			$this->response->redirect('articles/edit?id=' . $newArticle->getId(), 201);
		}
	}