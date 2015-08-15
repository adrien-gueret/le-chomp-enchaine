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
	Note qu'il s'agit d'un mot de passe temporaire. Il est conseillé de le changer à ta première connexion, en cliquant sur le lien "Profil".
	Ce n'est pas obligatoire, mais c'est mieux !
</p>
<p>
	Allez, à très vite sur <em>Le Chomp Enchaîné</em> !
</p>