<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Nom</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($view->all_categories as $category): ?>
			<tr>
				<td><?= $category->getId(); ?></td>
				<td><?= $category->prop('name'); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>