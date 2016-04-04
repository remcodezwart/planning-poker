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
			<td><?=$result->user_name?></td>
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
		<?php foreach ($this->chamber as $result) { ?>
		 <input class="chekbox" onclick="Answer(this.value)" type="radio" name="<?=$result->featureid ?>" value="1/<?=$result->featureid ?>"> 1
		 <input class="chekbox" onclick="Answer(this.value)" type="radio" name="<?=$result->featureid ?>" value="2/<?=$result->featureid ?>"> 2
		 <input class="chekbox" onclick="Answer(this.value)" type="radio" name="<?=$result->featureid ?>" value="3/<?=$result->featureid ?>"> 3
		 <input class="chekbox" onclick="Answer(this.value)" type="radio" name="<?=$result->featureid ?>" value="4/<?=$result->featureid ?>"> 4
		 <input class="chekbox" onclick="Answer(this.value)" type="radio" name="<?=$result->featureid ?>" value="5/<?=$result->featureid ?>"> 5
		 <input class="chekbox" onclick="Answer(this.value)" type="radio" name="<?=$result->featureid ?>" value="joker/<?=$result->featureid ?>">joker
		<br>
		 <?php
				}
		 ?>
	</form>
	<script>
    function Answer(value){
	    $.ajax({
	    	type: "POST",
	        url:"<?php echo Config::get('URL'); ?>chamber/answer_action",
	        data : {value: value, csrf_token: '<?= Csrf::makeToken();?>'}
	    });
    }
    </script>
    