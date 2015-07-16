<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Nom</th>
			<th>Date de publication</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($view->all_newspapers as $newspapers): ?>
			<tr>
				<td><?= $newspapers->getId(); ?></td>
				<td>
					<a href="<?= $view->base_url; ?>admin/newspapers/edit?id=<?= $newspapers->getId(); ?>">
						<?= $newspapers->prop('name'); ?>
					</a>
				</td>
				<td>
					<?php if(!$newspapers->prop('date_publication')): ?>
						Non publié
					<?php else: ?>
						Le <?= date('d/m/Y à H:i', strtotime($newspapers->prop('date_publication'))); ?>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>