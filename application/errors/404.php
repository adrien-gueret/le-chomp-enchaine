<?php
	//An instance of this class is automatically called by Eliya when a 404 error is thrown
	class Error_404
	{
		public function __construct(Eliya\Response $response)
		{
			$error_message	=	$response->error();
			//If default message for non-existed page
			if (substr($error_message, 0, 10) === 'Controller')
				$error_message	=	'La page demandée n\'existe pas !';

			$response->set(
				Eliya\Tpl::get('errors', [
			   		'error_number'	=>	404,
					'message'		=>	$error_message
				])
			);
		}
	}