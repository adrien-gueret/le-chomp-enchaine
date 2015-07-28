<?php
	class Controller_newspapers extends Controller_index
	{
		public function get_index($id_newspaper = null)
		{
			if (empty($id_newspaper))
				$this->_showIndex();
			else
				$this->_showNewspaper($id_newspaper);
		}

		protected function _showIndex()
		{
			$newspapers = Model_Newspapers::getAllPublished();

			if ($newspapers->isEmpty())
				$tpl_newspapers	=	Eliya\Tpl::get('newspapers/none');
			else
				$tpl_newspapers	=	Eliya\Tpl::get('newspapers/list', ['newspapers' => $newspapers]);

			$this->response->set(Eliya\Tpl::get('newspapers/index', [
				'tpl_newspapers' => $tpl_newspapers,
			]));
		}

		protected function _showNewspaper($id_newspaper)
		{
			$newspaper = Model_Newspapers::getById($id_newspaper);

			if (empty($newspaper)) {
				$this->response->error('Le journal demandé n\'existe pas ou plus.', 404);
				return;
			}

			$isPublished = ! is_null($newspaper->prop('date_publication'));
			$canReadUnpublished = $this->_currentUser->hasPermission(Model_Groups::PERM_READ_UNPUBLISHED_ARTICLES);

			if ( ! $isPublished && ! $canReadUnpublished) {
				$this->response->error('Le journal demandé n\'est pas ou plus publié.', 403);
				return;
			}

			$articles = $newspaper->load('articles');

			if ($articles->isEmpty())
				$tpl_articles = Eliya\Tpl::get('newspapers/no_articles');
			else
				$tpl_articles = Eliya\Tpl::get('newspapers/articles', ['articles' => $articles]);

			$this->response->set(Eliya\Tpl::get('newspapers/details', [
				'newspaper' => $newspaper,
				'tpl_articles' => $tpl_articles,
			]));
		}
	}