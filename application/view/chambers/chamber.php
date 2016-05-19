<div id="answer">
	<form action="<?php echo Config::get('URL'); ?>chamber/changeRoomName_action" method="post">
		<label>naam wijzigen van kamer</label>
		<input type="text" name="subject">
		<input type="hidden" name="id" value="<?=$_GET['id']?>">
		<input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
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
				<td style="display:block"><?=$result->feature?></td>
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
</div>
 
    
<script>
	chekAnswers();
    var Timer = setInterval(chekAnswers, 6000);
    function Answer(value){
        $.ajax({
            type: "POST",
            url:"<?php echo Config::get('URL'); ?>chamber/answer_action",
            data : {id: <?=$_GET['id'] ?>,value: value, csrf_token: '<?= Csrf::makeToken();?>'},
            dataType: "json", 
        });
    }
    function chekAnswers() {
            $.ajax({
            type: "POST",
            url:"<?php echo Config::get('URL'); ?>chamber/chekIfuseranswerAreFilled_action",
            data:{id: <?=$_GET['id'] ?>,csrf_token: '<?= Csrf::makeToken();?>'},
            success: function(data) {
            		
                    rows = $.parseJSON(data);
                    if (rows.succes != "do nothing") {
	                    content = $('#answer');
	                    var myNode = document.getElementById("answer");
	                    while (myNode.firstChild) {
	                        myNode.removeChild(myNode.firstChild);
	                    }
	                    content.append('<table id="table"><tr><th>gebruiker</th><th>feature</th><th>antwoord</th>');
	                    for(var r in rows){
	                        content = $('#table');
	                        content.append('<tr><td>'+rows[r].user_name+'</td><td>'+rows[r].feature+'</td><td>'+rows[r].answer+'</td></tr>');
	                    }
	                    content = $('#answer');
	                    content.append('</table>');
	                    clearInterval(Timer);
                	}
                  },
        });
    }
    </script>