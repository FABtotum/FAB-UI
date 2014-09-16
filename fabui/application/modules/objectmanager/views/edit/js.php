<script type="text/javascript">

$('#files_table').dataTable({
	"aaSorting": []
});


$('#save-object').on('click', save_object);


function save_object(){
	
	$("#save-object").addClass('disabled');
	$('#save-object').html('<i class="fa fa-save"></i> Saving...');
	
	$.ajax({
		type: "POST",
		url: "<?php echo module_url('objectmanager').'ajax/save_object.php' ?>",
		data: {object_id : <?php echo $_object->id ?>, name: $("#obj_name").val(), description: $("#obj_description").val()},
		dataType: 'json'
	}).done(function(response) {

		$("#save-object").removeClass('disabled');
		$('#save-object').html('<i class="fa fa-save"></i> Save');
		
		
		$.smallBox({
			title : "Object saved with success",
			color : "#659265",
			iconSmall : "fa fa-check bounce animated",
			timeout : 4000
		});

		$("#label-obj-name").html($("#obj_name").val());
				
	});
	
}


function ask_delete(id_file, file_name) {

	$.SmartMessageBox({
		title: "Attention!",
		content: "Remove <b>" + file_name + "</b> ?",
		buttons: '[No][Yes]'
	}, function(ButtonPressed) {
	   
		if (ButtonPressed === "Yes") {

			delete_file(id_file);
		}
		if (ButtonPressed === "No") {

		}

	});

}



function delete_file(id_file) {
    openWait('Deleting file..');
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('objectmanager/delete_file') ?>/" + id_file,
		dataType: 'json'
	}).done(function(response) {

		if (response.success == true) {
            document.location.href = document.location.href;

		} else {

			show_error(response.message);
		}
	});

}

</script>
