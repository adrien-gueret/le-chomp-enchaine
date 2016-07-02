<h1>Liste des catégories</h1>
<p>
	Des articles, ça se classe ! Retrouvez ci-dessous les différentes catégories dans
	lesquelles sont rangés scrupuleusement chacun de nos articles.
</p>
<hr />

<table>
	<thead>
		<tr>
			<th>Nom</th>
			<th>Total d'articles</th>
			<th>Voir</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($view->categories as $category): ?>
			<tr>
				<td><?= $category->prop('name'); ?></td>
				<td><?= $category->total_articles; ?></td>
				<td>
					<a href="<?= $category->getUrl(); ?>">Voir</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
