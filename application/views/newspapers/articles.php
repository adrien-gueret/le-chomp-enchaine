<div class="list-of-cards">
	<?php foreach($view->articles as $article): ?>
		<article class="card-list">
			<a href="<?= $article->getUrl(); ?>">
				<figure >
					<img src="<?= $article->getMainPictureURL(); ?>" alt="" />
					<figcaption>
						<h2><?= $article->prop('title'); ?></h2>
					</figcaption>
				</figure>
			</a>
		</article>
	<?php endforeach; ?>
</div>