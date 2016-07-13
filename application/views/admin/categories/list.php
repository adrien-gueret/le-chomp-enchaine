<div class="list-of-cards">
	<?php foreach($view->all_categories as $category): ?>
		<article class="card-list category">
			<a href="<?= $view->base_url; ?>admin/categories/edit?id=<?= $category->getId(); ?>">
				<figure>
					<img src="<?= $category->getMainPictureURL(); ?>" alt="" />
					<figcaption>
						<h3><?= $category->prop('name'); ?></h3>
						<p><?= $category->prop('description'); ?></p>
					</figcaption>
				</figure>
			</a>
		</article>
	<?php endforeach; ?>
</div>