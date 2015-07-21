<?php
	class Controller_index extends Controller_main
	{
		public function get_index()
		{
			$this->response->set(\Eliya\Tpl::get('index/index'));
		}
	}