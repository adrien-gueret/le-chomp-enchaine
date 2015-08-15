<?php
	class Controller_about extends Controller_index
	{
		public function get_index()
		{
			$this->response->set(\Eliya\Tpl::get('about/index'));
		}
	}