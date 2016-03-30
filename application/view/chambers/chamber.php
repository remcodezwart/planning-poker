<form action="#" method="post">
	<label>naam wijzigen van kamer</label>
	<input type="text" name="subject">
	<input type="submit" value="update">
</form>


	<table>
		<tr>
			<th>Eigenaar</th>
			<th>onderwerp</th>
		</tr>	
		<tr>
		<?php foreach ($this->chamber as $result) {?>
			<td><?=$result->Name?></td>
			<td><?=$result->subject?></td>
		<?php
			break;
			}
		?>
		</tr>
</table>
<table>
		<tr>
			<td colspan=10; class="Features">Features</td>
		</tr>
		<tr>
		<?php foreach ($this->chamber as $result) {?>
			<td class="block"><?=$result->feature?></td>
		<?php
			}
		?>
		</tr>
	</table>
	<form class="float" method="post" action="#">
		<?php for ($count=0; $count < 6; $count++) { 
		?>
		 <input class="chekbox" onclick="Answer(this.value)" id="1" type="radio" name="<?=$count ?>" value="1"> 1
		 <input class="chekbox" onclick="Answer(this.value" id="2" type="radio" name="<?=$count ?>" value="2"> 2
		 <input class="chekbox" onclick="Answer(this.value)" id="3" type="radio" name="<?=$count ?>" value="3"> 3
		 <input class="chekbox" onclick="Answer(this.value)" type="radio" name="<?=$count ?>" value="4"> 4
		 <input class="chekbox" onclick="Answer(this.value)" type="radio" name="<?=$count ?>" value="5"> 5
		 <input class="chekbox" onclick="Answer(this.value)" type="radio" name="<?=$count ?>" value="joker">joker
		<br>
		 <?php
			}
		 ?>
	</form>
	<script>
	function Answer(value,id){
		formData = "answer=" + value;
		 	$.ajax({
			    url:"answer.php",  
			    type: "POST",
   				data : formData,
			   });
	}
	</script>