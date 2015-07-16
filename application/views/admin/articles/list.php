<table>
	<thead>
		<tr>
			<th>Rubrique</th>
			<th>Titre</th>
			<th>Dernière modification</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($view->articles as $article): ?>
			<tr>
				<td><?= $article->load('section')->prop('name'); ?></td>
				<td>
					<a href="<?= $view->base_url; ?>admin/articles/edit?id=<?= $article->getId(); ?>">
						<?= $article->prop('title'); ?>
					</a>
				</td>
				<td>
					Le <?= date('d/m/Y à H:i', strtotime($article->prop('date_last_update'))); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>