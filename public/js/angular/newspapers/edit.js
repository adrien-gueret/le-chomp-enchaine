(function (angular) {
	'use strict';

	angular
		.module('editNewspaperModule', ['fileReader'])
		.controller('editNewspaperController', function() {
			var controller = this;
			var newspaperDataContainer = document.getElementById('newspaperData');

			controller.currentNewspaper = JSON.parse(newspaperDataContainer.innerHTML);
			newspaperDataContainer.parentNode.removeChild(newspaperDataContainer);

			controller.fileHandler = function($data) {
				controller.currentNewspaper.fileSrc = $data;
				controller.base64img = $data;
			};
		});
})(angular);