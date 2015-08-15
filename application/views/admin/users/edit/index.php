<?php if($view->edit_current): ?>
<h1>Modification de votre profil</h1>
<?php else: ?>
<h1>Modification d'un utilisateur</h1>
<?php endif; ?>
<hr>
<form action="<?= $view->base_url; ?>admin/users/edit?id=<?= $view->user->getId(); ?>" method="post">
	<input type="hidden" name="__method__" value="PUT" />
	<fieldset>
		<legend>Profil de <?= $view->user->prop('username'); ?></legend>
		<label for="user-name">Pseudo :</label>
		<input id="user-name" type="text" name="username" required 
			   value="<?= $view->user->prop('username'); ?>"
		/> <br />
		<label for="user-email">E-mail :</label>
		<input id="user-email" type="email" name="email" required 
			   value="<?= $view->user->prop('email'); ?>"
		/> <br />
		<label for="user-password">Mot de passe:</label>
		<input id="user-password" type="password" name="password" />
		<br /><small>Laissez vide pour ne pas changer le mot de passe</small>
		<br />
		<?php if(isset($view->groups)): ?>
			<label for="user-group">Groupe :</label>
			<select id="user-group" name="id_group">
				<?php foreach($view->groups as $group): ?>
					<option value="<?= $group->getId(); ?>"
							<?= $group == $view->usergroup ? 'selected': null; ?>>
						<?= $group->prop('group_name'); ?>
					</option>
				<?php endforeach; ?>
			</select>
		<?php endif; ?>
		<?php if($view->edit_current): ?>
			<br />
			<small>Par mesure de sécurité, vous devrez vous reconnecter après avoir modifié votre profil.</small>
		<?php endif; ?>
		<p><input type="submit" value="Modifier" /></p>
	</fieldset>
</form>