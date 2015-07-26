<?php
	class Controller_newspapers extends Controller_index
	{
		public function get_index()
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
	}