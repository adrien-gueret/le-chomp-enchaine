<h1>Modification d'un article</h1>
<hr />
<script id="articleData" type="application/json"><?= $view->article->toJSON() ;?></script>
<script src="<?= $view->base_url; ?>public/js/angular/articles/edit.js"></script>
<div ng-app="editArticleModule" ng-controller="editArticleController as editCtrl">
	<main ng-if="editCtrl.previewEnabled">
		<header ng-style="editCtrl.headerStyle">
			<h1 ng-bind="editCtrl.currentArticle.title"></h1>
		</header>
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
					</figure>
				</article>
			</fieldset>
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