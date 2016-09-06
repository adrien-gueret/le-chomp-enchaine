<?php
	class Controller_rss extends Controller_index
	{
        const ARTICLES_BY_PAGE = 10;

	    public function get_index($unused_param = 0)
		{
		    $this->response->type(\Eliya\Mime::XML)->isRaw(true);

            $categories = Model_Categories::getAll();
            $categoriesIdsToNames = [];

            foreach($categories as $category) {
                $categoriesIdsToNames[$category->getId()] = $category->prop('name');
            }

            $authors = Model_Users::getAll();
            $authorsIdsToNames = [];

            foreach($authors as $author) {
                $authorsIdsToNames[$author->getId()] = $author->prop('username');
            }

            $tpl_items = null;
            $articles   =   Model_Articles::getLast(1, self::ARTICLES_BY_PAGE);

            foreach($articles as $article) {
                $tpl_items  .=  \Eliya\Tpl::get('rss/item_article', [
                    'article' => $article,
                    'author' => $authorsIdsToNames[$article->id_author] ?: null,
                    'category' => $categoriesIdsToNames[$article->id_category] ?: null,
                ]);
            }

            $this->response->set(\Eliya\Tpl::get('rss/index', [
                'tpl_items' =>  $tpl_items,
            ]));
		}
	}