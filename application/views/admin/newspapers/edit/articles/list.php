<table>
	<thead>
	<tr>
		<th>ID</th>
		<th>Titre</th>
		<th>Auteur</th>
		<th>Dernière modification</th>
		<th>Dé-lier l'article</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($view->articles as $article): ?>
		<tr>
			<td><?= $article->getId(); ?></td>
			<td>
				<a href="<?= $view->base_url; ?>admin/articles/edit?id=<?= $article->getId(); ?>">
					<?= $article->prop('title'); ?>
				</a>
			</td>
			<td>
				<?= $article->load('author')->prop('username'); ?>
			</td>
			<td>
				Le <?= date('d/m/Y à H:i', strtotime($article->prop('date_last_update'))); ?>
			</td>
			<td>
				<form action="<?= $view->base_url; ?>admin/newspapers/edit/removeArticle?id=<?= $view->id_newspaper; ?>" method="post">
					<input type="hidden" name="__method__" value="PUT" />
					<input type="hidden" name="id_article" value="<?= $article->getId(); ?>" />
					<input type="submit" value="Dé-lier" />
				</form>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>