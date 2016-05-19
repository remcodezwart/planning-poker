<table>
	<tbody id="table">
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
	</tbody>
</table>
<table>
		<tr>
			<td colspan="2"><h3 style="display:inline">nieuwe feature toevoegen <label id="adding" onclick="add()" style="margin-left:5px">+</label></h3></td>
			<td><input type="text" id="newFeature" name="newFeature"></td>
		</tr>
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
		      			document.getElementById('result').innerHTML = "succes";
						element.parentElement.style.display = 'none';
					} else {
						document.getElementById('result').innerHTML = "error";
					}
	      	    }     
	        });
	    }
	    function edit(value) {
   			 var split = value.split("/");
   			 var currentId = split[0];
           $.ajax({
	            type: "POST",
	            url:"<?php echo Config::get('URL'); ?>chamber/edit_action",
	            data:{id: <?=$this->id[0]?>,value:value,csrf_token: '<?= Csrf::makeToken();?>'},
	            success: function(data) {
	            	var rows = $.parseJSON(data);
	            	if (rows.succes == "error") {
	            		document.getElementById('result').innerHTML = "Error somthing went wrong";
	                } else {
	                	document.getElementById('result').innerHTML = "succes";
	                }

           	}
	       });
	    }
	    function add()
	    {
	    	var feature;
	    	feature = document.getElementById("newFeature").value;
	    	$.ajax({
	            type: "POST",
	            url:"<?php echo Config::get('URL'); ?>chamber/addFeature_action",
	            data : {id: <?=$this->id[0]?>,add:feature, csrf_token: '<?= Csrf::makeToken();?>'},
	            success: function(data) {

	            	var value = $.parseJSON(data);
	            	
	            	content = $('#table');
	            
	            	var string = '<tr><td><input type=\"text\" id=\"' + value.id + "\" " + "value=\"" + value.feature + '\"></td> <td style=\"background-color:red\" onclick=\"Delete(this)\" id=\"' + value.id + "/" + value.chamber + '\">x</td><td onclick=\"edit(' + value.chamber + '/' + value.id  + '/' + 'document.getElementById(' + value.id + ').value' + '\")>' + 'wijzigen' + '</td></tr>';
	            	
	            	content.append(string);

	            	document.getElementById("newFeature").value = "";
	       		}
	        });
	    }
</script>
