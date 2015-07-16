<h1>Liste des utilisateurs</h1>
<?= $view->tpl_users; ?>

<form action="<?= $view->base_url; ?>admin/users" method="post">
	<fieldset>
		<legend>Créer un nouvel utilisateur</legend>
		<label for="user-name">Pseudo :</label>
		<input id="user-name" type="text" name="username" required /> <br />
		<label for="user-email">E-mail :</label>
		<input id="user-email" type="email" name="email" required /> <br />
		<label for="user-group">Groupe :</label>
		<select id="user-group" name="id_group">
			<?php foreach($view->groups as $group): ?>
				<option value="<?= $group->getId(); ?>"
						<?= $group->getId() == 2 ? 'selected': null; ?>>
					<?= $group->prop('group_name'); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<p><input type="submit" value="Créer" /></p>
	</fieldset>
</form>