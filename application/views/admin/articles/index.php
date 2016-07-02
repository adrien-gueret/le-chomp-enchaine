<h1>Vos articles</h1>
<?= $view->tpl_articles; ?>

<form action="<?= $view->base_url; ?>admin/articles" method="post">
	<fieldset>
		<legend>Créer un nouvel article</legend>
		<label for="article_title">Titre : </label>
		<input type="text" id="article_title" placeholder="Titre de l'article" name="title" required /><br />
		<label for="article_category">Catégorie: </label>
		<select id="article_category" name="id_category">
			<?php foreach($view->categories as $category): ?>
				<option value="<?= $category->getId(); ?>"><?= $category->prop('name'); ?></option>
			<?php endforeach; ?>
		</select>
		<p><input type="submit" value="Créer" /></p>
	</fieldset>
</form>