<?php $this->renderFeedbackMessages(); ?>

<h2>weet u zeker dat u deze kamer wil verwijderen?</h2>
	<form method="post" action="<?php echo Config::get('URL'); ?>chamber/DeleteChamber_action/?id=<?=$_GET['id']?>">
		<input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
		<input type="submit" value="Ja" class="submit">
	</form>