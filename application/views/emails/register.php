<p>
	Hello <b><?= $view->username; ?></b> !<br />
	Ton compte de r�dacteur sur <em>Le Chomp Encha�n�</em> vient d'�tre cr��.
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
	Note que ce mot de passe a �t� g�n�r� al�atoirement pour toi : tu es le seul � la connaitre, ne le perd donc
	pas ! Il n'est pas encore possible de le modifier pour le moment, mais �a arrivera peut-�tre si �a devient
	n�cessaire...
</p>
<p>
	Aller, � tr�s vite sur <em>Le Chomp Encha�n�</em> !
</p>