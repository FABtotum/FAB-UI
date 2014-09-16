<script type="text/javascript">

    var oTable;
    
	$(document).ready(function() {


           /*
	       * BASIC
	       */
           
           /*
		oTable = $('#objects_table').dataTable({
			"aaSorting": [],
			"bProcessing": true,
            "sAjaxSource": '<?php echo module_url('objectmanager').'ajax/table.php' ?>',
            "fnRowCallback": function ( row, data, index ){
                
                $('td', row).eq(1).addClass('hidden-xs');
                $('td', row).eq(2).addClass('hidden-xs');
                $('td', row).eq(3).addClass('hidden-xs');
                $('td', row).eq(4).addClass('text-right');
            }
		});
        */
        
        $('#objects_table').dataTable({
            "aaSorting": [],
            "bProcessing": true,
            "sAjaxSource": '<?php echo module_url('objectmanager').'ajax/all_objects_for_table.php' ?>'
            
        });

	});

	function delete_obj(obj_id, obj_name) {

		$.ajax({
			type: "POST",
			url: "<?php echo site_url('objectmanager/delete') ?>/" + obj_id,
			dataType: 'json'
		}).done(function(response) {

			if (response.success == true) {
                $('#objects_table').dataTable()._fnAjaxUpdate();
			} else {
				show_error(response.message);
			}
		});

	}
    
    
    
    function ask_delete(obj_id, obj_name){
        
        $.SmartMessageBox({
				title: "Attention!",
				content: "Remove <b>" + obj_name + "</b> ?",
				buttons: '[No][Yes]'
			}, function(ButtonPressed) {
				if (ButtonPressed === "Yes") {

					delete_obj(obj_id, obj_name);
				}
				if (ButtonPressed === "No") {

				}

			});
    }
    
    
    
    
    
</script>