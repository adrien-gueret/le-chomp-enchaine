<script id="articleData" type="application/json"><?= $view->article->toJSON() ;?></script>
<script src="<?= Library_Assets::get('js/angular/articles/read.js'); ?>"></script>
<main ng-app="readArticleModule" ng-controller="readArticleController as readCtrl">
	<header style="background-image: url(<?= $view->article->getMainPictureURL() ?>);">
		<h1><?= $view->article->prop('title'); ?></h1>
	</header>
	<aside class="article-infos">
		<?php if( $view->article->prop('section') !== null ): ?>
			Dans <b>"<?= $view->article->prop('section')->prop('name'); ?>"</b>
		<?php endif; ?>
		<?php if( $view->article->prop('author') !== null ): ?>
			par <b><a href="<?= $view->article->prop('author')->getUrl(); ?>"><?= $view->article->load('author')->prop('username'); ?></a></b>
		<?php endif ?>
		| Dernière modification le <b><?= date('d/m/Y', strtotime($view->article->prop('date_last_update'))); ?></b>
		<?php if ( ! empty($view->newspaper)): ?>
			| Publié dans le journal <b>"<a href="<?= $view->newspaper->getUrl(); ?>"><?= $view->newspaper->prop('name'); ?></a>"</b>
		<?php endif; ?>
	</aside>
	<p class="article-introduction"><?= $view->article->prop('introduction'); ?></p>
	<article ng-bind-html="readCtrl.currentArticle.content | markdown"></article>

	<footer class="article-infos">
		<?= $view->tpl_previous_article; ?>
		<div class="author card-list">
			<?php if( $view->article->prop('author') !== null ): ?>
				Rédigé par
				<b><a href="<?= $view->article->prop('author')->getUrl(); ?>"><?= $view->article->load('author')->prop('username'); ?></a></b>
			<?php endif; ?>
			<?php if ( ! empty($view->newspaper)): ?>
				pour le journal
				<b>"<a href="<?= $view->newspaper->getUrl(); ?>"><?= $view->newspaper->prop('name'); ?></a>"</b>
			<?php endif; ?>
		</div>
		<?= $view->tpl_next_article; ?>
	</footer>

	<hr />

	<!-- Facebook comments -->
	<div id="fb-root"></div>
	<script src="//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.4&appId=<?= \Eliya\Config('main')->FACEBOOK['APP_ID']; ?>"></script>

	<aside>
		<div class="fb-comments"
			 data-href="<?= $view->article->getUrl(); ?>"
			 data-width="100%"
			 data-numposts="10"></div>
	</aside>
</main>