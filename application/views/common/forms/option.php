<?php if($view->open_optgroup): ?>
	<optgroup label="<?= $view->group_name; ?>">
<?php endif; ?>

<option value="<?= $view->value; ?>"><?= $view->label; ?></option>

<?php if($view->close_optgroup): ?>
	</optgroup>
<?php endif; ?>