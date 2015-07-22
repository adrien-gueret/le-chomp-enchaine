<p>
	Hello <b><?= $view->username; ?></b> !<br />
	Ton compte de rédacteur sur <em>Le Chomp Enchaîné</em> vient d'être créé.
</p>
<p>
	Pour te connecter, il te suffit de te rendre sur <a href="<?= $view->login_url; ?>"><?= $view->login_url; ?></a>
	et d'y renseigner tes informations d'identification :
</p>
<ul>
	<li><b>Identifiant :</b> <?= $view->email; ?></li>
	<li><b>Mot de passe :</b> <?= $view->password; ?></li>
</ul>
<p>
	Note que ce mot de passe a été généré aléatoirement pour toi : tu es le seul à la connaitre, ne le perd donc
	pas ! Il n'est pas encore possible de le modifier pour le moment, mais ça arrivera peut-être si ça devient
	nécessaire...
</p>
<p>
	Aller, à très vite sur <em>Le Chomp Enchaîné</em> !
</p>