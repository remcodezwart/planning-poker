<?php $this->renderFeedbackMessages(); ?>

<form action="<?php echo Config::get('URL'); ?>chamber/CreateChamber_action" method="post">
	<label>Naam van de kamer</label><br>
	<input type="text" name="ChamberName"><br>
	<label>Onderwerp</label><br>
	<input type="text" name="Onderwerp"><br>
	<label>Features</label><br>
	<input type="text" name="feature1"><br>
	<input type="text" name="feature2"><br>
	<input type="text" name="feature3"><br>
	<input type="text" name="feature4"><br>
	<input type="text" name="feature5"><br>
	<input type="text" name="feature6"><br>
	<input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
	<input type="submit"  value="Verzenden">
</form>
<a href="user.php">annuleren</a>