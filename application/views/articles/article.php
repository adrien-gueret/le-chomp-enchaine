<script id="articleData" type="application/json"><?= $view->article->toJSON() ;?></script>
<script src="<?= Library_Assets::get('js/angular/articles/read.js'); ?>"></script>
<main ng-app="readArticleModule" ng-controller="readArticleController as readCtrl">
	<header style="background-image: url(<?= $view->article->getMainPictureURL() ?>);">
		<h1><?= $view->article->prop('title'); ?></h1>
	</header>
	<aside class="article-infos">
		Dans <b>"<?= $view->article->prop('section')->prop('name'); ?>"</b>
		par <b><?= $view->article->prop('author')->prop('username'); ?></b>
		| Dernière modification le <b><?= date('d/m/Y', strtotime($view->article->prop('date_last_update'))); ?></b>
		<?php if ( ! empty($view->newspaper)): ?>
			| Publié dans le journal <b>"<?= $view->newspaper->prop('name'); ?>"</b>
		<?php endif; ?>
	</aside>
	<p class="article-introduction"><?= $view->article->prop('introduction'); ?></p>
	<article ng-bind-html="readCtrl.currentArticle.content | markdown"></article>
</main>