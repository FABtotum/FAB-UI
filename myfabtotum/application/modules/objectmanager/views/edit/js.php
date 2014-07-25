<script type="text/javascript">

$('#files_table').dataTable({
	"aaSorting": []
});

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
