<?php $this->renderFeedbackMessages(); ?>

<form action="<?php echo Config::get('URL'); ?>chamber/CreateChamber_action" method="post">
	<input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
	<label>Naam van de kamer</label><br>
	<input type="text" name="ChamberName"><br>
	<label>Onderwerp</label><br>
	<input type="text" name="Onderwerp"><br>
	<label>Features</label><br>
	<label id="features">

	

	</label>
	<input type="submit"  value="Verzenden">
</form>

<script type="text/javascript">
	var AmoutOfFeatures = prompt("hoveel features?");

	content = $('#features');
    var myNode = document.getElementById("features");
    var count = 0;
	while(AmoutOfFeatures >= 1)
	{
        content.append('<input type="text" name="' + count + '"><br>');
        count++;
        AmoutOfFeatures--;
	}
</script> 