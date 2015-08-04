<script id="articleData" type="application/json"><?= $view->article->toJSON() ;?></script>
<script src="<?= Library_Assets::get('js/angular/articles/read.js'); ?>"></script>
<main ng-app="readArticleModule" ng-controller="readArticleController as readCtrl">
	<header style="background-image: url(<?= $view->article->getMainPictureURL() ?>);">
		<h1><?= $view->article->prop('title'); ?></h1>
	</header>
	<aside class="article-infos">
		Dans <b>"<?= $view->article->prop('section')->prop('name'); ?>"</b>
		par <b><a href="<?= $view->article->prop('author')->getUrl(); ?>"><?= $view->article->load('author')->prop('username'); ?></a></b>
		| Dernière modification le <b><?= date('d/m/Y', strtotime($view->article->prop('date_last_update'))); ?></b>
		<?php if ( ! empty($view->newspaper)): ?>
			| Publié dans le journal <b>"<a href="<?= $view->newspaper->getUrl(); ?>"><?= $view->newspaper->prop('name'); ?></a>"</b>
		<?php endif; ?>
	</aside>
	<p class="article-introduction"><?= $view->article->prop('introduction'); ?></p>
	<article ng-bind-html="readCtrl.currentArticle.content | markdown"></article>

	<footer class="article-infos">
		<?= $view->tpl_previous_article; ?>
		<div class="author">
			Rédigé par
			<b><a href="<?= $view->article->prop('author')->getUrl(); ?>"><?= $view->article->load('author')->prop('username'); ?></a></b>
			<?php if ( ! empty($view->newspaper)): ?>
				pour le journal
				<b>"<a href="<?= $view->newspaper->getUrl(); ?>"><?= $view->newspaper->prop('name'); ?></a>"</b>
			<?php endif; ?>
		</div>
		<?= $view->tpl_next_article; ?>
	</footer>
</main>