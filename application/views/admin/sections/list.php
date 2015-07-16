<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Nom</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($view->all_sections as $section): ?>
			<tr>
				<td><?= $section->getId(); ?></td>
				<td><?= $section->prop('name'); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>