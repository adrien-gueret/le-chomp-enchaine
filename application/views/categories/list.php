<h1>Liste des catégories</h1>
<p>
	Des articles, ça se classe ! Retrouvez ci-dessous les différentes catégories dans
	lesquelles sont rangés scrupuleusement chacun de nos articles.
</p>
<hr />

<div class="list-of-cards">
	<?php foreach($view->categories as $category): ?>
		<article class="card-list category">
			<a href="<?= $category->getUrl(); ?>">
				<figure>
					<img src="<?= $category->getMainPictureURL(); ?>" alt="" />
					<figcaption>
						<h3><?= $category->prop('name'); ?></h3>
						<p><?= $category->prop('description'); ?></p>
						<small><?= $category->total_articles; ?> article<?= $category->total_articles > 1 ? 's' : ''; ?></small>
					</figcaption>
				</figure>
			</a>
		</article>
	<?php endforeach; ?>
</div>