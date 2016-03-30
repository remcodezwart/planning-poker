<?php $this->renderFeedbackMessages(); ?>

<h2>Weet U zeker dat u uw acount wil verwijderen?</h2>
	<form method="post" action="<?php echo Config::get('URL'); ?>user/deleteUser_action">
		<input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
		<input type="submit" value="Ja" class="submit">
	</form>