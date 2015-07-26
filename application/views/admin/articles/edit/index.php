<h1>Modification d'un article</h1>
<hr />
<script id="articleData" type="application/json"><?= $view->article->toJSON() ;?></script>
<script src="<?= Library_Assets::get('js/angular/articles/edit.js'); ?>"></script>
<div ng-app="editArticleModule" ng-controller="editArticleController as editCtrl">
	<main ng-if="editCtrl.previewEnabled">
		<header ng-style="editCtrl.headerStyle">
			<h1 ng-bind="editCtrl.currentArticle.title"></h1>
		</header>
		<p class="article-introduction">{{editCtrl.currentArticle.introduction}}</p>
		<article ng-bind-html="editCtrl.currentArticle.content | markdown"></article>
	</main>

	<form action="<?= $view->base_url; ?>admin/articles/edit?id=<?= $view->article->getId(); ?>" method="post">
		<input type="hidden" name="__method__" value="PUT" />
		<input type="hidden" name="base64img" value="{{editCtrl.base64img}}" ng-if="editCtrl.base64img" />
		<div ng-hide="editCtrl.previewEnabled">
			<fieldset>
				<legend>Informations principales</legend>
				<label for="article-title">Titre : </label>
				<input type="text"
					   id="article-title"
					   placeholder="Titre de l'article"
					   name="title"
					   ng-model="editCtrl.currentArticle.title"
					   required /><br />
				<label for="article-section">Rubrique : </label>
				<select id="article-section" name="id_section" ng-model="editCtrl.currentArticle.section.id">
					<?php foreach($view->all_sections as $section): ?>
						<option value="<?= $section->getId(); ?>">
							<?= $section->prop('name'); ?>
						</option>
					<?php endforeach; ?>
				</select><br />
				<label for="article-image">Image de présentation : </label>
				<input type="file" file-reader="editCtrl.fileHandler($data)" id="article-image" />
				<article ng-if="editCtrl.currentArticle.fileSrc">
					<figure class="preview">
						<img ng-src="{{editCtrl.currentArticle.fileSrc}}" alt="Image" />
						<figcaption>700px * 400px</figcaption>
					</figure>
				</article>
			</fieldset>

			<p class="label-container">
				<label for="article-introduction">Introduction de l'article</label> <br />
				<small>Deux phrases ou trois et pas de formattage spécial : que du texte simple.</small>
			</p>
			<textarea id="article-introduction"
					  required
					  name="introduction"
					  class="article introduction"
					  markdown-sanitize
					  ng-model="editCtrl.currentArticle.introduction"></textarea>

			<p class="label-container">
				<label for="article-content">Contenu de l'article</label> <br />
				<small>
					Le coeur de l'article. Formattage possible via
					<a target="_blank" href="http://daringfireball.net/projects/markdown/syntax">Markdown</a>.
				</small>
			</p>
			<textarea class="article"
				  id="article-content"
				  required
				  markdown-sanitize
				  ng-model="editCtrl.currentArticle.content"
				  name="content"></textarea>
		</div>
		<hr />

		<button type="button"
			   ng-bind="editCtrl.previewEnabled ? 'Modifier' : 'Prévisualiser'"
			   ng-click="editCtrl.previewEnabled = !editCtrl.previewEnabled"></button>
		<input type="submit" value="Sauvegarder" />
	</form>
</div>