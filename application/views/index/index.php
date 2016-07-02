<h2>Les derniers articles</h2>
<div class="list-of-cards">
	<?php foreach($view->articles as $article): ?>
		<article class="card-list">
			<a href="<?= $article->getUrl(); ?>">
				<figure>
					<img src="<?= $article->getMainPictureURL(); ?>" alt="" />
					<figcaption>
						<h2><?= $article->prop('title'); ?></h2>
						<small>Publié le <?= date('d/m/Y', strtotime($article->prop('date_publication'))); ?></small>
					</figcaption>
				</figure>
			</a>
		</article>
	<?php endforeach; ?>
</div>