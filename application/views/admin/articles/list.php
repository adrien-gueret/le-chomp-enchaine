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
					|
					<?php if($article->prop('is_published')): ?>
						<form action="<?= $view->base_url; ?>admin/articles/edit/unpublish?id=<?= $article->getId(); ?>"
							  method="post"
							  data-publish="0">
							<input type="hidden" name="__method__" value="PUT" />
							<input type="submit" value="Dépublier" />
						</form>
					<?php else: ?>
						<form action="<?= $view->base_url; ?>admin/articles/edit/publish?id=<?= $article->getId(); ?>"
							  method="post"
							  data-publish="1">
							<input type="hidden" name="__method__" value="PUT" />
							<input type="submit" value="Publier" />
						</form>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>