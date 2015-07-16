<h1>Modification d'un fanzine</h1>

<form action="<?= $view->base_url; ?>admin/newspapers/edit?id=<?= $view->newspaper->getId(); ?>" method="post">
	<input type="hidden" name="__method__" value="PUT" />
	<fieldset>
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
		<p><input type="submit" value="Sauvegarder" /></p>
	</fieldset>
</form>

<h2>Liste des articles</h2>

<?= $view->tpl_articles; ?>

<form action="<?= $view->base_url; ?>admin/newspapers/edit/addArticle?id=<?= $view->newspaper->getId(); ?>" method="post">
	<input type="hidden" name="__method__" value="PUT" />
	<fieldset>
		<legend>Ajouter un nouvel article</legend>

		<?php if(empty($view->unpublished_articles)): ?>
			<p>Il n'y a pas d'articles disponibles.</p>
		<?php else: ?>
			<label for="form-article">Sélectionnez l'article : </label>
			<select id="form-article" name="id_article">
				<?php foreach($view->unpublished_articles as $article): ?>
				<option value="<?= $article->id; ?>"><?= $article->title; ?></option>
				<?php endforeach; ?>
			</select>
			<p><input type="submit" value="Ajouter" /></p>
		<?php endif; ?>
	</fieldset>
</form>