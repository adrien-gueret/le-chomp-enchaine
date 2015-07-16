<table>
	<thead>
		<tr>
			<th>Pseudo</th>
			<th>E-mail</th>
			<th>Groupe</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($view->all_users as $user): ?>
			<tr>
				<td><?= $user->username; ?></td>
				<td><?= $user->email; ?></td>
				<td><?= $user->usergroup_group_name; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>