<h1>Vos articles</h1>
<?= $view->tpl_articles; ?>

<form action="<?= $view->base_url; ?>admin/articles" method="post">
	<fieldset>
		<legend>Créer un nouvel article</legend>
		<label for="article_title">Titre : </label>
		<input type="text" id="article_title" placeholder="Titre de l'article" name="title" required /><br />
		<label for="article_section">Rubrique: </label>
		<select id="article_section" name="id_section">
			<?php foreach($view->sections as $section): ?>
				<option value="<?= $section->getId(); ?>"><?= $section->prop('name'); ?></option>
			<?php endforeach; ?>
		</select>
		<p><input type="submit" value="Créer" /></p>
	</fieldset>
</form>