<h1>Liste des rubriques</h1>
<?= $view->tpl_sections; ?>

<form action="<?= $view->base_url; ?>admin/sections" method="post">
	<fieldset>
		<legend>Créer une nouvelle rubrique</legend>
		<label for="section-name">Nom de la rubrique :</label>
		<input id="section-name" type="text" name="name" required />
		<p><input type="submit" value="Créer" /></p>
	</fieldset>
</form>