<script src="<?= Library_Assets::get('js/angular/categories/manage.js'); ?>"></script>
<form action="<?= $view->base_url; ?>admin/categories/<?= $view->end_action_url; ?>"
	  method="post"
	  ng-app="manageCategoryModule" ng-controller="manageCategoryController as categoryCtrl"
      <?= $view->edit_mode ? 'ng-init="categoryCtrl.fileSrc = \''.$view->category_picture.'\'"' : ''; ?> >
	<fieldset>
		<?php if ($view->edit_mode): ?>
			<input type="hidden" name="__method__" value="PUT" />
			<legend>Editer une catégorie</legend>
		<?php else: ?>
			<legend>Créer une nouvelle catégorie</legend>
		<?php endif; ?>
		<label for="category-name">Nom de la catégorie :</label>
		<input id="category-name" type="text" name="name" value="<?= empty($view->category_name) ? '' : $view->category_name; ?>" required /><br />
		<label for="category-image">Image de présentation : </label>
		<input type="file" <?= $view->edit_mode ? '' : 'required' ?> file-reader="categoryCtrl.fileHandler($data)" id="category-image" />
		<input type="hidden" name="base64img" value="{{categoryCtrl.base64img}}" ng-if="categoryCtrl.base64img" />
		<article>
			<figure class="preview">
				<img ng-src="{{categoryCtrl.fileSrc}}" alt="Image" ng-if="categoryCtrl.fileSrc" />
				<figcaption>700px * 400px</figcaption>
			</figure>
		</article>
		<p><input type="submit" value="<?= $view->edit_mode ? 'Editer' : 'Créer' ?>" /></p>
	</fieldset>
</form>