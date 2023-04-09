<!-- Scripts for Facebook and Twitter -->
<div id="fb-root"></div>
<script async src="//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.4&appId=<?= \Eliya\Config('main')->FACEBOOK['APP_ID']; ?>"></script>
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

<script id="articleData" type="application/json"><?= $view->article->toJSON() ;?></script>
<script src="<?= Library_Assets::get('js/angular/articles/read.js'); ?>"></script>
<main ng-app="readArticleModule" ng-controller="readArticleController as readCtrl">
	<header style="background-image: url(<?= $view->article->getMainPictureURL() ?>);">
		<h1><?= $view->article->prop('title'); ?></h1>
	</header>

	<aside class="article-infos">
		<?php if( $view->category !== null ): ?>
			Dans <a href="<?= $view->category->getUrl(); ?>"><b>"<?= $view->category->prop('name'); ?>"</b></a>
		<?php endif; ?>
		<?php if( $view->article->prop('author') !== null ): ?>
			par <b><a href="<?= $view->article->prop('author')->getUrl(); ?>"><?= $view->article->load('author')->prop('username'); ?></a></b>
		<?php endif ?>
		<br />Dernière modification le <b><?= date('d/m/Y', strtotime($view->article->prop('date_last_update'))); ?></b>
		<?php if($view->currentUser->hasPermission(Model_Groups::PERM_EDIT_OTHER_ARTICLES) || $view->currentUser->equals($view->article->load('author'))): ?>
			| <a href="<?= $view->base_url; ?>admin/articles/edit?id=<?= $view->article->getId(); ?>">Éditer cet aticle</a>
		<?php endif ?>
		<?php if($view->currentUser->hasPermission(Model_Groups::PERM_PUBLISH_OTHER_ARTICLES) || $view->currentUser->equals($view->article->load('author'))): ?>
			|
			<?php if($view->article->prop('is_published')): ?>
				<span class="publication-status published">Cet article est publié</span>
				<form action="<?= $view->base_url; ?>admin/articles/edit/unpublish?id=<?= $view->article->getId(); ?>"
					  method="post"
					  data-publish="0">
					<input type="hidden" name="__method__" value="PUT" />
					<input type="submit" value="Dépublier" />
				</form>
			<?php else: ?>
				<span class="publication-status unpublished">Cet article n'est PAS publié</span>
				<form action="<?= $view->base_url; ?>admin/articles/edit/publish?id=<?= $view->article->getId(); ?>"
					  method="post"
					  data-publish="1">
					<input type="hidden" name="__method__" value="PUT" />
					<input type="submit" value="Publier" />
				</form>
				| <a href="<?= $view->article->getURl(true); ?>">URL de partage de pré-publication</a>
			<?php endif; ?>
		<?php endif ?>
	</aside>

	<?= $view->templateShareLinks; ?>

	<p class="article-introduction"><?= $view->article->prop('introduction'); ?></p>
	<article ng-bind-html="readCtrl.currentArticle.content | markdown"></article>

	<footer class="article-infos">
		<div class="author card-list">
			<?php if( $view->article->prop('author') !== null ): ?>
				Rédigé par
				<b><a href="<?= $view->article->prop('author')->getUrl(); ?>"><?= $view->article->load('author')->prop('username'); ?></a></b>
			<?php endif; ?>
		</div>
	</footer>

	<?= $view->templateShareLinks; ?>

	<hr />

	<!-- Facebook comments -->
	<aside>
		<div class="fb-comments"
			 data-href="<?= $view->article->getUrl(); ?>"
			 data-width="100%"
			 data-numposts="10"></div>
	</aside>
</main>