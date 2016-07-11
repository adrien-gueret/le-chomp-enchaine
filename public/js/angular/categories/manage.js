(function (angular) {
	'use strict';

	angular
		.module('manageCategoryModule', ['fileReader'])
		.controller('manageCategoryController', function() {
			var controller = this;
			
			controller.fileHandler = function($data) {
				controller.base64img = $data;
				controller.fileSrc = $data;
			};
		});
})(angular);