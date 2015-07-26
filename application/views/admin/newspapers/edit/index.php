<h1>Modification d'un fanzine</h1>
<script id="newspaperData" type="application/json">{"fileSrc": "<?= $view->newspaper->getMainPictureURL();?>"}</script>
<script src="<?= Library_Assets::get('js/angular/newspapers/edit.js'); ?>"></script>
<form action="<?= $view->base_url; ?>admin/newspapers/edit?id=<?= $view->newspaper->getId(); ?>" method="post">
	<input type="hidden" name="__method__" value="PUT" />
	<fieldset ng-app="editNewspaperModule" ng-controller="editNewspaperController as editCtrl">
		<input type="hidden" name="base64img" value="{{editCtrl.base64img}}" ng-if="editCtrl.base64img" />
		<legend>Informations principales</legend>
		<label for="newspaper-title">Nom : </label>
		<input type="text"
			   id="newspaper-title"
			   placeholder="Nom du numéro"
			   name="name"
			   value="<?= $view->newspaper->prop('name'); ?>"
			   required /><br />
		<label for="newspaper-publish">Publié :</label>
		<input type="checkbox"
			   id="newspaper-publish"
			   name="isPublished"
			   value="1"
			   <?= $view->newspaper->prop('date_publication') ? 'checked': ''; ?> />
		<br />
		<label for="newspaper-image">Image de présentation : </label>
		<input type="file" file-reader="editCtrl.fileHandler($data)" id="newspaper-image" />
		<article>
			<figure class="preview">
				<img ng-src="{{editCtrl.currentNewspaper.fileSrc}}" alt="Image" />
				<figcaption>230px * 230px</figcaption>
			</figure>
		</article>
		<p><input type="submit" value="Sauvegarder" /></p>
	</fieldset>
</form>

<h2>Liste des articles</h2>

<?= $view->tpl_articles; ?>

<form action="<?= $view->base_url; ?>admin/newspapers/edit/addArticle?id=<?= $view->newspaper->getId(); ?>" method="post">
	<input type="hidden" name="__method__" value="PUT" />
	<fieldset>
		<legend>Ajouter un nouvel article</legend>

		<?php if(empty($view->tpl_unpublished_articles)): ?>
			<p>Il n'y a pas d'articles disponibles.</p>
		<?php else: ?>
			<label for="form-article">Sélectionnez l'article : </label>
			<select id="form-article" name="id_article">
				<?= $view->tpl_unpublished_articles; ?>
			</select>
			<p><input type="submit" value="Ajouter" /></p>
		<?php endif; ?>
	</fieldset>
</form>