<h1>Liste des fanzines</h1>
<?= $view->tpl_newspapers; ?>

<form action="<?= $view->base_url; ?>admin/newspapers" method="post">
	<fieldset>
		<legend>Créer un nouveau numéro</legend>
		<label for="newspaper-name">Nom du numéro :</label>
		<input id="newspaper-name" type="text" name="name" required />
		<p><input type="submit" value="Créer" /></p>
	</fieldset>
</form>