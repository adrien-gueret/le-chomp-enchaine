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
			$articles		=	Model_Articles::getFromNewspaper($newspaper);

			if (empty($articles)) {
				$tpl_articles	=	\Eliya\Tpl::get('admin/newspapers/edit/articles/none');
			} else {
				$tpl_articles	=	\Eliya\Tpl::get('admin/newspapers/edit/articles/list', [
					'articles'			=>	$articles,
					'total_articles'	=>	count($articles),
					'id_newspaper'		=>	$id,
				]);
			}

			$tpl_unpublished_articles = null;
			$unpublished_articles = Model_Articles::getUnpublished();

			if ( ! empty($unpublished_articles)) {
				$total_articles = count($unpublished_articles);
				$last_id_section = null;

				foreach($unpublished_articles as $key => $article) {
					$is_last_index = $key + 1 === $total_articles;

					$open_optgroup = $article->section_id !== $last_id_section;
					$close_optgroup = $open_optgroup && $last_id_section !== null || $is_last_index;

					$tpl_unpublished_articles .= \Eliya\Tpl::get('common/forms/option', [
						'value' => $article->id,
						'label' => $article->title,
						'open_optgroup' => $open_optgroup,
						'close_optgroup' => $close_optgroup,
						'group_name' => $article->section_name,
					]);

					$last_id_section = $article->section_id;
				}
			}

			$this->response->set(\Eliya\Tpl::get('admin/newspapers/edit/index', [
				'newspaper'					=>	$newspaper,
				'tpl_unpublished_articles'	=>	$tpl_unpublished_articles,
				'tpl_articles'				=>	$tpl_articles,
			]));
		}

		public function put_index($id, $name, $isPublished = false, $base64img = null)
		{
			$newspaper = Model_Newspapers::getById($id);

			$newspaper->updateMainPicture($base64img);

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

			$article->prop('newspaper', $newspaper);
			$article->load('author');
			$article->load('section');

			Model_Articles::update($article);
			Model_Articles::cleanArticlesPositions($id);

			$this->get_index($id);
		}

		public function put_removeArticle($id, $id_article)
		{
			$article	=	Model_Articles::getById($id_article);

			$article->prop('newspaper', null);
			$article->load('author');
			$article->load('section');

			Model_Articles::update($article);
			Model_Articles::cleanArticlesPositions($id);

			$this->get_index($id);
		}

		public function put_moveArticle($id, $id_article, $moveTo)
		{
			$article	=	Model_Articles::getById($id_article);

			Model_Articles::updateArticlePosition($article, $moveTo);

			$this->get_index($id);
		}
	}