<table>
	<tr>
		<th>feature</th>
		<th>feature verwijderen</th>
		<th>feature wijzigen</th>
	</tr>
<?php
 	foreach($this->answer as $chamber){
?>	
	<tr>
		<td><input type="text" id="<?=$chamber->id?>" value="<?=$chamber->feature ?>"/></td>
		<td style="background-color:red" onclick="Delete(this);" id="<?=$chamber->id?>/<?=$chamber->chamber_id ?>">x</td>
		<td onclick="edit('<?=$chamber->id?>' + '/' + '<?= $chamber->chamber_id ?>' + '/' +
		document.getElementById('<?=$chamber->id?>').value);" >wijzigen</td>
	</tr>
<?php
	}
?>
</table>
<label id="result"></label>
<script>
		 function Delete(element){
	        $.ajax({
	            type: "POST",
	            url:"<?php echo Config::get('URL'); ?>chamber/deleteFeature_action",
	            data : {id: <?=$this->id[0]?>,value: element.id, csrf_token: '<?= Csrf::makeToken();?>'},
				success: function(data) {
	
	      		    var rows = $.parseJSON(data);
	      			if (rows.succes == "gelukt") {
		      			document.getElementById('result').innerHTML = "gelukt";
						element.parentElement.style.display = 'none';
					} else {
						document.getElementById('result').innerHTML = "error";
					}
	      	    }     
	        });
	    }
	      
	    function edit(test) {
           $.ajax({
	            type: "POST",
	            url:"<?php echo Config::get('URL'); ?>chamber/edit_action",
	            data:{id: <?=$this->id[0]?>,csrf_token: '<?= Csrf::makeToken();?>'},
	            success: function(data) {
	            	 var rows = $.parseJSON(data);
	            	 console.log(rows);
	            	if (true) {

	            	}
	                   document.getElementById('result').innerHTML = data;
           	}
	       });
	    }
</script>