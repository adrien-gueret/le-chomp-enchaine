<?php
	class Controller_about extends Controller_index
	{
		public function get_index($unused_param = 0)
		{
			$this->response->set(\Eliya\Tpl::get('about/index'));
		}
	}