<div class="list-of-cards">
	<?php foreach($view->newspapers as $newspaper): ?>
		<article class="card-list">
			<a href="<?= $newspaper->getUrl(); ?>">
				<figure >
					<img src="<?= $newspaper->getMainPictureURL(); ?>" alt="" />
					<figcaption>
						<h2><?= $newspaper->prop('name'); ?></h2>
						<small>Publié le <?= date('d/m/Y', strtotime($newspaper->prop('date_publication'))); ?></small>
					</figcaption>
				</figure>
			</a>
		</article>
<?php endforeach; ?>
</div>