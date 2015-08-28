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
		<?php foreach($view->all_newspapers as $newspapers): ?>
			<tr>
				<td><?= $newspapers->getId(); ?></td>
				<td><?= $newspapers->prop('name'); ?></td>
				<td>
					<?php if(!$newspapers->prop('date_publication')): ?>
						Non publié
					<?php else: ?>
						Le <?= date('d/m/Y à H:i', strtotime($newspapers->prop('date_publication'))); ?>
					<?php endif; ?>
				</td>
				<td>
					<a href="<?= $view->base_url; ?>admin/newspapers/edit?id=<?= $newspapers->getId(); ?>">Éditer</a>
					 | 
					<a href="<?= $newspapers->getUrl() ?>">Lire</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>