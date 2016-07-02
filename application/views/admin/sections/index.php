<h1>Liste des catégories</h1>
<?= $view->tpl_categories; ?>

<form action="<?= $view->base_url; ?>admin/categories" method="post">
	<fieldset>
		<legend>Créer une nouvelle catégories</legend>
		<label for="category-name">Nom de la catégorie :</label>
		<input id="category-name" type="text" name="name" required />
		<p><input type="submit" value="Créer" /></p>
	</fieldset>
</form>