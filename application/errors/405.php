<?php
	//An instance of this class is automatically called by Eliya when a 405 error is thrown
	class Error_405
	{
		public function __construct(Eliya\Response $response)
		{
			$error_message	=	$response->error();
			//If default message for non-existed page
			if (substr($error_message, 0, 10) === 'Controller')
				$error_message	=	'La page demandée ne peut être appelée de cette façon.';

			$response->set(
				Eliya\Tpl::get('errors', [
			   		'error_number'	=>	405,
					'message'		=>	$error_message
				])
			);
		}
	}