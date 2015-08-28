<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Nom</th>
			<th>Date de publication</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($view->all_newspapers as $newspaper): ?>
			<tr>
				<td><?= $newspaper->getId(); ?></td>
				<td><?= $newspaper->prop('name'); ?></td>
				<td>
					<?php if(!$newspaper->prop('date_publication')): ?>
						Non publié
					<?php else: ?>
						Le <?= date('d/m/Y à H:i', strtotime($newspaper->prop('date_publication'))); ?>
					<?php endif; ?>
				</td>
				<td>
					<a href="<?= $view->base_url; ?>admin/newspapers/edit?id=<?= $newspaper->getId(); ?>">Éditer</a>
					 | 
					<a href="<?= $newspaper->getUrl() ?>">Lire</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>