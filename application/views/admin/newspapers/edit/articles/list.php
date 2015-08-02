<table>
	<thead>
	<tr>
		<th>ID</th>
		<th>Titre</th>
		<th>Auteur</th>
		<th>Dernière modification</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($view->articles as $key => $article): ?>
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
				<form action="<?= $view->base_url; ?>admin/newspapers/edit/removeArticle?id=<?= $view->id_newspaper; ?>"
					  method="post">
					<input type="hidden" name="__method__" value="PUT" />
					<input type="hidden" name="id_article" value="<?= $article->getId(); ?>" />
					<input type="submit" value="Dé-lier" />
				</form>
				<?php if ($view->total_articles > 1): ?>
					<?php if ($key > 0): ?>
						<form action="<?= $view->base_url; ?>admin/newspapers/edit/moveArticle?id=<?= $view->id_newspaper; ?>"
							  method="post">
							<input type="hidden" name="__method__" value="PUT" />
							<input type="hidden" name="moveTo" value="<?= Model_Articles::MOVE_TO_TOP; ?>" />
							<input type="hidden" name="id_article" value="<?= $article->getId(); ?>" />
							<input type="submit" value="▲" />
						</form>
					<?php endif; ?>
					<?php if ($key < $view->total_articles - 1): ?>
						<form action="<?= $view->base_url; ?>admin/newspapers/edit/moveArticle?id=<?= $view->id_newspaper; ?>"
							  method="post">
							<input type="hidden" name="__method__" value="PUT" />
							<input type="hidden" name="moveTo" value="<?= Model_Articles::MOVE_TO_BOTTOM; ?>" />
							<input type="hidden" name="id_article" value="<?= $article->getId(); ?>" />
							<input type="submit" value="▼" />
						</form>
					<?php endif; ?>

				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>