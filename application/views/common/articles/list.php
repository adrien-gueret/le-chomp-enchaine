<div class="list-of-cards">
	<?php foreach($view->articles as $article): ?>
		<article class="card-list article">
			<a href="<?= $article->getUrl(); ?>">
				<p class="section-name"><?= $article->load('section')->prop('name'); ?></p>
				<figure style="background-image: url(<?= $article->getMainPictureURL(); ?>);">
					<figcaption>
						<h2><?= $article->prop('title'); ?></h2>
					</figcaption>
				</figure>
			</a>
		</article>
	<?php endforeach; ?>
</div>