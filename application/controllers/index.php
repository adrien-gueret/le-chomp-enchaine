<?php
	class Controller_index extends Controller_main
	{
		public function get_index($page = 1)
		{
			$tpl_articles = Eliya\Tpl::get('common/articles/list', ['articles' => Model_Articles::getLast($page)]);

			$this->response->set(\Eliya\Tpl::get('index/index', [
				'tpl_articles' => $tpl_articles
			]));
		}
	}