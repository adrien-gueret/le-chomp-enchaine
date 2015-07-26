<?php foreach($view->newspapers as $newspaper): ?>
	<!--<a href="<?= $newspaper->getUrl(); ?>">-->
		<figure>
			<img src="<?= $newspaper->getMainPictureURL(); ?>" alt="" />
			<h2><?= $newspaper->prop('name'); ?></h2>
			<p>Publié le <?= date('d/m/Y', strtotime($newspaper->prop('date_publication'))); ?></p>
		</figure>
	<!--</a>-->
<?php endforeach; ?>
