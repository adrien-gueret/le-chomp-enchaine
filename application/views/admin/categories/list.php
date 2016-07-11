<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Nom</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($view->all_categories as $category): ?>
			<tr>
				<td><?= $category->getId(); ?></td>
				<td><?= $category->prop('name'); ?></td>
				<td>
					<a href="<?= $view->base_url; ?>admin/categories/edit?id=<?= $category->getId(); ?>">Éditer</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>