<h2>Les derniers articles</h2>

<?= $view->tpl_articles; ?>

<nav>
	<?php if($view->nbr_pages > 1): ?>
		<?php for($i = 1; $i <= $view->nbr_pages; $i++): ?>
			<?php if($view->current_page == $i): ?>
				<span class="selected-link"><?= $i; ?></span>
			<?php else:  ?>
				<a	href="<?= $view->base_url . $i . '/'; ?>">[<?= $i; ?>]</a>
			<?php endif; ?>
		<?php endfor; ?>
	<?php endif; ?>
</nav>