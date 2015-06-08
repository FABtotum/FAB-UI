<script type="text/javascript">

$('#files_table').dataTable({
	"aaSorting": [],
	"aoColumns": [
		{ "bSortable": false },
		null,
		null,
		null,
		null,
		{"bSortable": false}]
});


$('#save-object').on('click', save_object);
$(".bulk-button").on('click', bulk_actions);

$(".select-all").on('click', function(){
       			
       		var state = $(this).is(":checked");
       		
       		$(".table > tbody").find(":checkbox").each(function(){	
       			if($(this).is(":checked") != state){
       				$(this).trigger("click");
       			}
       		});
       });


function save_object(){
	
	$("#save-object").addClass('disabled');
	$('#save-object').html('<i class="fa fa-save"></i> Saving...');
	
	$.ajax({
		type: "POST",
		url: "<?php echo module_url('objectmanager').'ajax/save_object.php' ?>",
		data: {object_id : <?php echo $_object->id ?>, name: $("#obj_name").val(), description: $("#obj_description").val(), private: $("#private").is(':checked') ? 1 : 0},
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
    
    var ids = new Array();
    ids.push(id_file);
    
	delete_files(ids);

}


function bulk_actions(){
		
	var action = $( ".bulk-select option:selected" ).val();
	
	if(action == ""){
		show_message("Please select an action");
		return false;
	}
	
	switch(action){
		case 'delete':
			bulk_delete();
			break;
	}	
		   		
}


function delete_files(list){
    	
	$(".bulk-button").addClass("disabled");
	$(".bulk-button").html("Deleting...");
	
	$.ajax({
			type: "POST",
			url: "<?php echo site_url('objectmanager/delete_file') ?>",
			dataType: 'json',
			data: {ids: list}
		}).done(function(response) {

			if (response.success == true) {
            
            	document.location.href = document.location.href;

			} else {
	
				show_error(response.message);
			}
			
		});

}


function bulk_delete(){
    	
    	
	var ids = new Array();
	
	var boxes = $(".table tbody").find(":checkbox:checked");
	
	if(boxes.length > 0){
		   		
		boxes.each(function() {
			ids.push($(this).attr("id").replace("check_", ""));
		});
		
		bulk_ask_delete(ids);			

	}else{
		 show_message("Please select at least 1 file");
		 return false;  			
	}
	
	
}


function show_message(message){
    	
    	$.SmartMessageBox({
				title: "<i class='fa fa-info-circle'></i> Information",
				content: message,
				buttons: '[Ok]'
			}, function(ButtonPressed) {
				if (ButtonPressed === "OK") {
				}
				
			});
    	
}
    
    
function bulk_ask_delete(ids){
    	
	$.SmartMessageBox({
			title: "<i class='fa fa-warning txt-color-orangeDark'></i> Warning!",
			content: "Do you really want to remove the selected files",
			buttons: '[No][Yes]'
		}, function(ButtonPressed) {
			if (ButtonPressed === "Yes") {

				delete_files(ids);
			}
			if (ButtonPressed === "No") {

			}

		});
}
    



</script>
