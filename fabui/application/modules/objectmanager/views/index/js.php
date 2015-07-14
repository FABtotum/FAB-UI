<script type="text/javascript">

    var oTable;
    
	$(document).ready(function() {
		
		$('.progress-bar').progressbar({
			
		});



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
        
       oTable = $('#objects_table').dataTable({
            "aaSorting": [],
            "bProcessing": true,
            "sAjaxSource": '<?php echo module_url('objectmanager').'ajax/all_objects_for_table.php' ?>',
            "fnDrawCallback" : fnCallBack,
            "aoColumns": [
  					{ "bSortable": false },
  					null,
 	 				null,
  					null,
  					null,
  					{"bSortable": false}]
            
       });
        
       $("[rel=tooltip]").tooltip();
        
        
        
       $(".bulk-button").on('click', bulk_actions);
       
       $(".select-all").on('click', function(){
       			
       		var state = $(this).is(":checked");
       		
       		$(".table > tbody").find(":checkbox").each(function(){	
       			if($(this).is(":checked") != state){
       				$(this).trigger("click");
       			}
       		});
       });
        
        
		
	});

	function delete_obj(obj_id, obj_name) {

		var ids = new Array();
		
		ids.push(obj_id);
		delete_objects(ids);

	}
    
    
    
    function ask_delete(obj_id, obj_name){
        
        $.SmartMessageBox({
				title: "<i class='fa fa-warning txt-color-orangeDark'></i> Warning!",
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
    
    
    function fnCallBack(){
    	 $(".checkbox").on('click', function(){
        });
    }
    
    
    
    function delete_objects(list){
    	
    	$(".bulk-button").addClass("disabled");
    	$(".bulk-button").html("Deleting...");
    	
    	$.ajax({
				type: "POST",
				url: "<?php echo site_url('objectmanager/delete') ?>",
				dataType: 'json',
				data: {ids: list}
			}).done(function(response) {
	
				if (response.success == true) {
	                oTable._fnAjaxUpdate();
	                
				} else {
					show_error(response.message);
				}
				
				$(".bulk-button").removeClass("disabled");
				$(".bulk-button").html("Apply");
				
			});

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
    
    function bulk_delete(){
    	
    	
    	var ids = new Array();
    	
    	var boxes = $(".table tbody").find(":checkbox:checked");
    	
    	if(boxes.length > 0){
    		   		
    		boxes.each(function() {
				ids.push($(this).attr("id").replace("check_", ""));
			});
			
			bulk_ask_delete(ids);			

    	}else{
    		 show_message("Please select at least 1 object");
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
				content: "Do you really want to remove the selected objects",
				buttons: '[No][Yes]'
			}, function(ButtonPressed) {
				if (ButtonPressed === "Yes") {

					delete_objects(ids);
				}
				if (ButtonPressed === "No") {

				}

			});
    	
    }
    
    
    
    
    
</script>