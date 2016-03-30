<?php $this->renderFeedbackMessages(); ?>

<h2>Ingelogd</h2>
<p>Opmerking alleen de eigenaar kan zijn/haar kamer verwijderen</p>
<table>
	<tr>
		<th>Naam</th>
		<th>Onderwerp</th>
		<th>Eigenaar</th>
		<th></th>
		<th></th>
	</tr>
<?php foreach($this->chambers as $chamber){
?>	
	<tr>
		<td><?=$chamber->Name ?></td>
		<td><?=$chamber->subject ?></td>
		<td><?=$chamber->owner ?></td>
		<td><a class="no-white" target="_blank" href="http://localhost/huge/chamber/index/?id=<?=$chamber->id?>">Kamer ingaan</a></td>
		<td><a class="no-white" href="http://localhost/huge/chamber/deletechamber/?id=<?=$chamber->id?>">kamer verwijderen</a></td>
	</tr>
<?php
	}
?>
</table>
<a href="http://localhost/huge/chamber/createChamber">Kamer toevoegen</a><br>