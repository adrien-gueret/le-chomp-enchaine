<table>
	<thead>
		<tr>
			<th>Catégorie</th>
			<th>Titre</th>
			<th>Dernière modification</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($view->articles as $article): ?>
			<tr>
				<td><?= $article->load('category')->prop('name'); ?></td>
				<td><?= $article->prop('title'); ?></td>
				<td>
					Le <?= date('d/m/Y à H:i', strtotime($article->prop('date_last_update'))); ?>
				</td>
				<td>
					<a href="<?= $view->base_url; ?>admin/articles/edit?id=<?= $article->getId(); ?>">Éditer</a>
					 | 
					<a href="<?= $article->getUrl() ?>">Lire</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>