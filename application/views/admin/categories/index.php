<h1>Liste des catégories</h1>
<?= $view->tpl_categories; ?>

<script src="<?= Library_Assets::get('js/angular/categories/create.js'); ?>"></script>
<form action="<?= $view->base_url; ?>admin/categories"
	  method="post"
	  ng-app="createCategoryModule" ng-controller="createCategoryController as categoryCtrl">
	<fieldset>
		<legend>Créer une nouvelle catégories</legend>
		<label for="category-name">Nom de la catégorie :</label>
		<input id="category-name" type="text" name="name" required /><br />
		<label for="category-image">Image de présentation : </label>
		<input type="file" required file-reader="categoryCtrl.fileHandler($data)" id="category-image" />
		<input type="hidden" name="base64img" value="{{categoryCtrl.base64img}}" ng-if="categoryCtrl.base64img" />
		<article>
			<figure class="preview">
				<img ng-src="{{categoryCtrl.base64img}}" alt="Image" ng-if="categoryCtrl.base64img" />
				<figcaption>700px * 400px</figcaption>
			</figure>
		</article>
		<p><input type="submit" value="Créer" /></p>
	</fieldset>
</form>