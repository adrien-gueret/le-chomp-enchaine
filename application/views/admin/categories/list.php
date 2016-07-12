<table>
	<tbody>
		<?php foreach($view->all_categories as $category): ?>
			<tr>
				<td><img src="<?= $category->getMainPictureUrl(); ?>" /></td>
				<th><h3><?= $category->prop('name'); ?></h3></th>
				<td>
					<a href="<?= $view->base_url; ?>admin/categories/edit?id=<?= $category->getId(); ?>">Éditer</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>