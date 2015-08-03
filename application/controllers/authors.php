<?php
	class Controller_authors extends Controller_index
	{
		public function get_index($id_author = null)
		{
			if((empty($id_author)))
				$this->response->error('Aucun utilisateur n\'a été demandé.', 404);
			else
				$this->_showAuthor($id_author);
		}

		protected function _showAuthor($id_author)
		{
			$author = Model_Users::getById($id_author);

			if (empty($author)) {
				$this->response->error('L\'utilisateur demandé n\'existe pas ou plus.', 404);
				return;
			}

			$canReadUnpublished = $this->_currentUser->hasPermission(Model_Groups::PERM_READ_UNPUBLISHED_ARTICLES);

			if($canReadUnpublished)
				$articles = $author->getLinkedArticles();
			else
				$articles = $author->getPublishedArticles();

			if ($articles->count() === 0)
				$tpl_articles = Eliya\Tpl::get('authors/no_articles');
			else
				$tpl_articles = Eliya\Tpl::get('common/articles/list', ['articles' => $articles]);

			$this->response->set(Eliya\Tpl::get('authors/details', [
				'author' => $author,
				'tpl_articles' => $tpl_articles,
			]));
		}
	}