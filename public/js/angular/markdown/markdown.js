(function (angular) {
	'use strict';

	angular
		.module('markdown', ['ngSanitize'])
		.service('RemarkableService', function($window) {
			if (!$window.Remarkable)
				throw new ReferenceError('Markdown library not found: please include it');

			var SYMBOLS = {
				'==':	'center',
				'<=':	'left',
				'=>':	'right'
			};
			var REGEXPS = {
				YOUTUBE_EMBED: /https?:\/\/www\.youtube\.com\/embed\/.+/i,
				MP3: /\.mp3(\?.*)?$/i
			};

			var markdownParser = new Remarkable();

			/* START:  Create a custom plugins for medias
			 // ==imageReference   : Centered img
			 // =>imageReference   : Img floatted to the right
			 // <=imageReference   : Img floatted to the left
			 */
			function customMediaParser(state) {
				var align = SYMBOLS[state.src.substr(0, 2)];

				if ( ! align) {
					return false;
				}

				state.push({
					type: 'customMedia',
					inlineMode: false,
					align: align,
					reference: state.src.substr(2)
				});

				state.pos = state.posMax;

				return true;
			}

			function customMediaRender(tokens, thisIndex, options, referencesData) {
				var imgToken = tokens[thisIndex];
				var imgReference = referencesData.references[imgToken.reference.toUpperCase()];

				if (!imgReference) {
					return '';
				}

				var href = imgReference.href;
				var media = '';
				var escapedTitle = imgReference.title ? Remarkable.utils.escapeHtml(imgReference.title) : '';
				var mediaType = 'video';

				// Check if youtube video
				if (REGEXPS.YOUTUBE_EMBED.test(href)) {
					media = '<div><iframe src="' + href + '"></iframe></div>';
				}
				// Check if MP3
				else if (REGEXPS.MP3.test(href)) {
					media = '<audio controls src="' + href + '"></audio>';
					mediaType = 'audio';

					if (escapedTitle) {
						escapedTitle += ' - ';
					}

					escapedTitle += '<a href="' + href + '" target="_blank">Lien vers le .mp3</a>';
				}
				// Else we consider it's an image
				else {
					var imageTitle = escapedTitle ? ' title="' + escapedTitle + '"' : '';
					media = '<img src="' + href + '" alt="Image"' + imageTitle + ' />';
					mediaType = 'image';
				}

				// Allow links into figcaption
				if (escapedTitle) {
					escapedTitle	=	new Remarkable({html: true}).renderInline(escapedTitle, {});
				}

				var startFigure = '<figure class="' + mediaType + ' ' + imgToken.align + '">';
				var endFigure = '</figure>';
				var figcaption = escapedTitle ? '<figcaption>' + escapedTitle.replace(/  /g, '<br />') + '</figcaption>' : '';

				return startFigure + media + figcaption + endFigure;
			}

			function customMedia(md) {
				md.inline.ruler.push('customMedia', customMediaParser);
				md.renderer.rules.customMedia = customMediaRender;
			}

			markdownParser.use(customMedia);

			/* END: customMedia plugin */

			function parseElement(domElement) {
				domElement.innerHTML	=	markdownParser.render(domElement.textContent);
				domElement.classList.add('parsed');
			}

			return	{
				parseElement: parseElement,
				parseText: markdownParser.render.bind(markdownParser)
			};
		})
		.service('getUnsafeHtml', function($sce) {
			return function(text) {
				var trustedHtml = $sce.getTrustedHtml(text);

				if (!trustedHtml.length) {
					return '';
				}

				// Handle all special chars like quotes and accents
				var tmpDiv = document.createElement('div');
				tmpDiv.innerHTML = trustedHtml;

				// Need to manually handle our custom arrows
				return tmpDiv.innerHTML.replace(/&lt;=/gi, '<=').replace(/=&gt;/gi, '=>');
			}
		})
		.filter('markdown', function($sce, RemarkableService, getUnsafeHtml) {
			return function(text) {
				return $sce.trustAsHtml(RemarkableService.parseText(getUnsafeHtml(text)));
			};
		})
		.directive('markdownSanitize', function() {
			return {
				restrict: 'A',
				scope: {value: '=ngModel'},
				controller: function($scope, getUnsafeHtml) {
					$scope.value = getUnsafeHtml($scope.value);
				}
			};
		})
})(angular);