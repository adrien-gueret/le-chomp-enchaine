(function (angular) {
	'use strict';

	angular
		.module('fileReader', [])
		.directive('fileReader', function() {
			return {
				restrict: 'A',
				scope: {
					callback: '&fileReader',
					dataType: '@fileReaderType' //Could be ArrayBuffer, BinaryString, Text or DataURL (default)
				},
				controller: function($scope, $element) {
					var dataType = $scope.dataType || 'DataURL';

					$element.bind('change', function(event) {
						var files = event.target.files;

						for(var i = 0, l = files.length; i< l; i++) {
							(function(file) {
								var fileReader = new FileReader();
								fileReader.onload = function() {
									$scope.$evalAsync($scope.callback({$data: this.result}));
								};

								fileReader['readAs' + dataType](file);
							})(files[i]);
						}
					});
				}
			};
		});
})(angular);