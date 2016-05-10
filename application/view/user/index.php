<?php $this->renderFeedbackMessages(); ?>

<h2>Ingelogd</h2>
<p>Opmerking alleen de eigenaar kan zijn/haar kamer verwijderen</p>
<table>
	<tr>
		<th>Naam</th>
		<th>Onderwerp</th>
		<th>Eigenaar</th>
		<th style="display:hidden;border:0px;"></th>
		<th style="display:hidden;border:0px;"></th>
	    <th style="display:hidden;border:0px;"></th>
		<th>eens?</th>
	</tr>
<?php
 	foreach($this->chambers as $chamber){
?>	
	<tr>
		<td><?=$chamber->name ?></td>
		<td><?=$chamber->subject ?></td>
		<td><?=$chamber->user_name ?></td>
		<td><a class="no-white" target="_blank" href="http://localhost/huge/chamber/index/?id=<?=$chamber->chamberid?>">Kamer ingaan</a></td>
		<td><a class="no-white" href="http://localhost/huge/chamber/deletechamber/?id=<?=$chamber->chamberid?>">kamer verwijderen</a></td>
		<td><a class="no-white" href="http://localhost/huge/chamber/editChamber/?id=<?=$chamber->chamberid?>">kamer editen</a></td>
		<td><?=$chamber->eens?></td>
	</tr>
<?php
	}
?>
</table>
<a href="http://localhost/huge/chamber/createChamber">Kamer toevoegen</a><br>