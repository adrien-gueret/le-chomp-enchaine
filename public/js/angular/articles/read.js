(function (angular) {
	'use strict';

	angular
		.module('readArticleModule', ['markdown'])
		.controller('readArticleController', function() {
			var controller = this;
			var articleDataContainer = document.getElementById('articleData');

			controller.currentArticle = JSON.parse(articleDataContainer.innerHTML);

			articleDataContainer.parentNode.removeChild(articleDataContainer);
		});
})(angular);