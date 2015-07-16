(function (angular) {
	'use strict';

	angular
		.module('editArticleModule', ['markdown', 'fileReader'])
		.controller('editArticleController', function(getUnsafeHtml) {
			var controller = this;
			var articleDataContainer = document.getElementById('articleData');

			function setHeaderImage() {
				if (controller.currentArticle.fileSrc)
					controller.headerStyle.backgroundImage = 'url(' + controller.currentArticle.fileSrc + ')';
			}

			controller.previewEnabled = false;
			controller.currentArticle = JSON.parse(articleDataContainer.innerHTML);
			controller.currentArticle.title = getUnsafeHtml(controller.currentArticle.title);
			controller.headerStyle = {};
			setHeaderImage();

			controller.fileHandler = function($data) {
				controller.currentArticle.fileSrc = $data;
				controller.base64img = $data;
				setHeaderImage();
			};

			articleDataContainer.parentNode.removeChild(articleDataContainer);
		});
})(angular);