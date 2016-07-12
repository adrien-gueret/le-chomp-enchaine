<h1>Liste des catégories</h1>
<p>
	Des articles, ça se classe ! Retrouvez ci-dessous les différentes catégories dans
	lesquelles sont rangés scrupuleusement chacun de nos articles.
</p>
<hr />

<table>
	<tbody>
		<?php foreach($view->categories as $category): ?>
			<tr>
				<td><a href="<?= $category->getUrl(); ?>"><img src="<?= $category->getMainPictureUrl(); ?>" /></a></td>
				<td class="title">
					<a href="<?= $category->getUrl(); ?>">
						<h3><?= $category->prop('name'); ?></h3>
						<p><?= $category->total_articles; ?> article<?= $category->total_articles > 1 ? 's' : ''; ?></p>
					</a>
				</td>
				<td class="extra-link">
					<a href="<?= $category->getUrl(); ?>">Voir</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
