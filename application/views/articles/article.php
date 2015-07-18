<script id="articleData" type="application/json"><?= $view->article->toJSON() ;?></script>
<script src="<?= $view->static_url; ?>js/angular/articles/read.js"></script>
<main ng-app="readArticleModule" ng-controller="readArticleController as readCtrl">
	<header style="background-image: url(<?= $view->article->getMainPictureURL() ?>);">
		<h1><?= $view->article->prop('title'); ?></h1>
	</header>
	<p class="article-introduction"><?= $view->article->prop('introduction'); ?></p>
	<article ng-bind-html="readCtrl.currentArticle.content | markdown"></article>
</main>