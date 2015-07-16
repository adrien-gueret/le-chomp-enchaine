<?php
	//An instance of this class is automatically called by Eliya when a 405 error is thrown
	class Error_405
	{
		public function __construct(Eliya\Response $response)
		{
			$response->set(
				Eliya\Tpl::get('errors', [
			   		'error_number'	=>	405,
					'message'		=>	$response->error()
				])
			);
		}
	}