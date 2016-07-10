(function (angular) {
	'use strict';

	angular
		.module('createCategoryModule', ['fileReader'])
		.controller('createCategoryController', function() {
			var controller = this;
			
			controller.fileHandler = function($data) {
				console.log($data);
				controller.base64img = $data;
			};
		});
})(angular);