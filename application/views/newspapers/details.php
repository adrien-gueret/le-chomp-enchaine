<h1><?= $view->newspaper->prop('name'); ?></h1>
<?php if(!is_null($view->newspaper->prop('date_publication'))): ?>
	<small>Publié le <?= date('d/m/Y', strtotime($view->newspaper->prop('date_publication'))); ?></small>
<?php endif; ?>
<hr />
<?= $view->tpl_articles; ?>