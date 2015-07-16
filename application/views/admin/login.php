<?php if(isset($view->errorMessage)): ?>
	<b><?= $view->errorMessage; ?></b>
<?php endif; ?>

<h1>Connexion à l'administration</h1>
<p>
	Veuillez vous identifier afin de pouvoir accéder à cette partie du site.
</p>

<form action="<?= $view->base_url; ?>admin/login" method="post">
	<fieldset>
		<legend>Connexion</legend>
		<label for="login-email">E-mail :</label>
		<input id="login-email" type="email" name="email" required /> <br />
		<label for="login-pass">Mot de passe :</label>
		<input id="login-pass" type="password" name="password" required />
		<p><input type="submit" value="Se connecter" /></p>
	</fieldset>
</form>