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
				SOUNDCLOUD_EMBED: /https?:\/\/api\.soundcloud\.com\/tracks\/.+/i,
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
				// Maybe SoundCloud ?
				else if(REGEXPS.SOUNDCLOUD_EMBED.test(href)) {
					var soundcloud_url = 'https://w.soundcloud.com/player/?';
					var params = [
						'url=' + href, 'auto_play=false', 'hide_related=true',
						'show_comments=false', 'show_reposts=false', 'visual=true'
					];

					media = '<div><iframe src="' + encodeURI(soundcloud_url + params.join('&')) + '"></iframe></div>';
				}
				// Else we consider it's an image
				else {
					// Rewrite local images to be protocol agnostic
					if(href.indexOf("http://static.lechompenchaine.fr") > -1)
						href = href.substr(5);

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
		.directive('markdownButtons', function() {
			return {
				restrict: 'E',
				scope: {textarea: '@'},
				template: '<div><button type="button" ng-click="wrapWith(\'*\')"><em>*Italique*</em></button>' +
									'<button type="button" ng-click="wrapWith(\'__\')"><strong>__Gras__</strong></button>' +
									'<button type="button" ng-click="insertBefore(\'## \')"><strong>## Titre</strong></button>' +
									'<button type="button" ng-click="insertLink()"><ins>Lien</ins></button><br />' +
									'<button type="button" ng-click="insertMedia(\'<=\')">&lt;=Média à gauche</button>' +
									'<button type="button" ng-click="insertMedia(\'==\')">==Média centré</button>' +
									'<button type="button" ng-click="insertMedia(\'=>\')">=&gt;Média à droite</button></div>',
				controller: function($scope, $document) {
					var textarea = $document[0].getElementById($scope.textarea);

					if (!textarea) {
						return;
					}

					function getTextareaInfo() {
						return {
							scrollTop: textarea.scrollTop,
							selection: {
								start: textarea.selectionStart,
								end: textarea.selectionEnd
							},
							text: {
								beforeSelected: textarea.value.substring(0, textarea.selectionStart),
								afterSelected: textarea.value.substring(textarea.selectionEnd),
								selected: textarea.value.substring(textarea.selectionStart, textarea.selectionEnd)
							}
						};
					}

					function validChange() {
						textarea.focus();

						var event = new Event('input');
						textarea.dispatchEvent(event);
					}

					$scope.wrapWith = function(wrapperBefore, wrapperAfter) {
						var textareaInfos = getTextareaInfo();
						wrapperAfter = wrapperAfter || wrapperBefore;

						textarea.value	=	textareaInfos.text.beforeSelected +
															wrapperBefore +
															textareaInfos.text.selected +
															wrapperAfter +
															textareaInfos.text.afterSelected;

						textarea.selectionStart	=	textareaInfos.selection.start + wrapperBefore.length;
						textarea.selectionEnd		=	textareaInfos.selection.start + textareaInfos.text.selected.length + wrapperBefore.length;

						validChange();

						textarea.scrollTop = textareaInfos.scrollTop;
					};

					$scope.insertBefore = function(textToInsert) {
						var textareaInfos = getTextareaInfo();

						textarea.value	=	textareaInfos.text.beforeSelected +
															textToInsert +
															textareaInfos.text.selected +
															textareaInfos.text.afterSelected;

						textarea.selectionStart	=	textareaInfos.selection.start + textToInsert.length;
						textarea.selectionEnd		=	textareaInfos.selection.start + textareaInfos.text.selected.length + textToInsert.length;

						validChange();

						textarea.scrollTop = textareaInfos.scrollTop;
					};

					$scope.insertMedia = function(textToInsert) {
						var mediaId = 'media_' + ((Date.now() * Math.random()).toString(36)).substring(0, 6);
						$scope.insertBefore("\n" + textToInsert + mediaId + "\n");

						var textareaInfos = getTextareaInfo();

						var mediaLink					=	'http://lien_vers_media/';
						var mediaDescription	= ' "Description optionnelle"';
						var mediaOptions 			=	mediaLink + mediaDescription;

						textarea.value += "\n[" + mediaId + ']: ' + mediaOptions;

						textarea.selectionStart	=	textarea.value.length - mediaOptions.length;
						textarea.selectionEnd		=	textarea.value.length - mediaDescription.length;

						validChange(textareaInfos);

						textarea.scrollTop = textarea.scrollHeight;
					};

					$scope.insertLink = function() {
						$scope.wrapWith('[', '](http://lien_a_inserer)');
					};
				}
			};
		});
})(angular);