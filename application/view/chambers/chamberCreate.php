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
	var done = false;
	var content = $('#features');
    var myNode = document.getElementById("features");
    var count = 0;

	amoutOfFeatures();
	function amoutOfFeatures(){
		var AmoutOfFeatures = prompt("hoveel features?(limiet 99999)");
		done = featuresGenerate(AmoutOfFeatures);
		while(done == false){
			AmoutOfFeatures = prompt("hoveel features?(limiet 99999)(voer een geldige waarden in!)");
			done = featuresGenerate(AmoutOfFeatures);
		}
	}

    function featuresGenerate(AmoutOfFeatures){
		while(AmoutOfFeatures >= 1) {
				if(AmoutOfFeatures >= 100000) {
					return false;
				}
		        content.append ('<input type="text" name="' + count + '"><br>');
		        count++;
		        AmoutOfFeatures--;
			}
			return true;
	}
</script> 