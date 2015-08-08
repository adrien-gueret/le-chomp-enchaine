<table>
	<thead>
		<tr>
			<th>Pseudo</th>
			<th>E-mail</th>
			<th>Groupe</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($view->all_users as $user): ?>
			<tr>
				<td><?= $user->username; ?></td>
				<td><?= $user->email; ?></td>
				<td><?= $user->usergroup_group_name; ?></td>
				<td>
					<?php if($view->currentUser->getId() !== $user->id): ?>
					<form class="removeUser" action="<?= $view->base_url; ?>admin/users"
					  method="post">
						<input type="hidden" name="__method__" value="DELETE" />
						<input type="hidden" name="id_user" value="<?= $user->id; ?>" />
						<input type="submit" value="Supprimer">
					</form>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<script type="text/javascript">
for(var form of document.querySelectorAll(".removeUser"))
{
	form.addEventListener('submit', function(e){
		if( ! confirm("Voulez-vous vraiment supprimer cet utilisateur ?"))
			e.preventDefault();

	});
}
</script>