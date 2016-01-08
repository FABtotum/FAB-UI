<script type="text/javascript">

    var oTable;
    
	$(document).ready(function() {
        
       oTable = $('#objects_table').dataTable({
            "aaSorting": [],
            "autoWidth": false,
            "bProcessing": true,
            "sAjaxSource": '<?php echo module_url('objectmanager').'ajax/all_objects_for_table.php' ?>',
            "fnDrawCallback" : fnCallBack,
  			"fnRowCallback": function (row, data, index ){
  				$('td', row).eq(0).addClass('hidden');
  				$('td', row).eq(1).attr('width', '20px');
  				$('td', row).eq(2).addClass('center table-checkbox');
  				$('td', row).eq(2).attr('width', '20px');
  				
  				$('td', row).eq(4).addClass('hidden-xs');
  				$('td', row).eq(5).addClass('hidden-xs');
  				$('td', row).eq(6).addClass('hidden-xs');
  			}
            
       });
        
       $("[rel=tooltip]").tooltip();
        
       $(".bulk-button").on('click', bulk_actions);
       
      
       
       
       $(".select-all").on('click', function(){
			var that = this;
			$(this).closest("table").find("tr > td input:checkbox").each(function() {
				this.checked = that.checked;			
			});   		
	 });
       

        
		
	});
	
	
	function fnFormatDetails(oTable, nTr) {
		
		var aData = oTable.fnGetData(nTr);
		var objectInfo = aData[0].split('-');
		var objectId   = objectInfo[0];
		var objectName = objectInfo[1];
	
		var editUrl = '<?php echo site_url('objectmanager/edit') ?>' + '/' + objectId;
		var downloadUrl = '<?php echo site_url('objectmanager/download/object/') ?>/' + objectId ;
		var addFiles = '<?php echo site_url('objectmanager/file/add') ?>/' + objectId;
		
		var edit_button = '<a rel="tooltip" data-placement="bottom" data-original-title="Edit the Object" href="' + editUrl + '" class="btn btn-primary details-button"><i class="fa fa-pencil"></i> Edit</a>';
		var download_button = '<a rel="tooltip" data-placement="bottom"  data-original-title="Save all object\'s files on your computer. You can use them in the third party software." href="' + downloadUrl + '" class="btn btn-info details-button"><i class="fa fa-download"></i> Download</a>';
		var delete_button = '<a rel="tooltip" data-placement="bottom" data-original-title="Delete the file" href=\'javascript:ask_delete(' + objectId +', "' + objectName + '");\' file-id="' + objectId + '" class="btn btn-danger details-button pull-right file-delete"><i class="fa fa-trash"></i> Delete</a>';
		
		var addfiles_button = '<a rel="tooltip" data-placement="bottom"  data-original-title="Add more files to this object." href="' + addFiles + '" class="btn btn-success details-button"><i class="fa fa-plus"></i> Add files</a>';
		
		
		return '<div>' + edit_button +  download_button + addfiles_button + delete_button + '</div>';
		
	}

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
    	
    	
    	$("#objects_table tbody > tr").on("click", function(e) {
    		
    		var tag = e.target.nodeName;
    		
    		if(tag == 'TD' || tag == "I"){
    		
				var nTr = $(this);
				
				var aCell = $(this).find("a i[data-toggle='row-detail']");
				
				
				oTable.$('tr').each( function () {
				    if( oTable.fnIsOpen( this ) ) {
				    	$(this).find("a i[data-toggle='row-detail']").removeClass("fa-chevron-down").addClass("fa-chevron-right");
				    	$(this).removeClass("info");
				    	oTable.fnClose(this);
				    }
				});
			 
				if (oTable.fnIsOpen(nTr)) {
					/* This row is already open - close it */
					aCell.removeClass("fa-chevron-down").addClass("fa-chevron-right");
					this.title = "Show Details";
					nTr.removeClass("info");
					oTable.fnClose(nTr);
					$("[rel=tooltip], [data-rel=tooltip]").tooltip();
				} else {
					/* Open this row */
					nTr.addClass("info");
					aCell.removeClass("fa-chevron-right").addClass("fa-chevron-down");
					oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr), "details");
					$("[rel=tooltip], [data-rel=tooltip]").tooltip();
				}
				return false;
			
			}

		});
    }
    
    
    
    function delete_objects(list){
    	
    	$(".bulk-button").addClass("disabled");
    	$(".bulk-button[data-action='delete']").html("<i class='fa fa-spinner'></i> Deleting...");
    	
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
				
				$(".bulk-button[data-action='delete']").html("<i class='fa fa-trash'></i> Delete");
				
			});

    }
    
    function bulk_actions(){
		
		var action = $( this ).attr('data-action');
		
		if(action == ""){
			show_message("Please select an action");
			return false;
		}
		
		switch(action){
			case 'delete':
				bulk_delete();
				break;
			case 'download':
				bulk_download();
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
    
    
    function bulk_download(){
    	var ids = new Array();
    	var boxes = $(".table tbody").find(":checkbox:checked");
    	
    	if(boxes.length > 0){
    		   		
    		boxes.each(function() {
				ids.push($(this).attr("id").replace("check_", ""));
			});		
			bulk_ask_download(ids);			

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
    
    
    function bulk_ask_download(ids){
    	$.SmartMessageBox({
				title: "<i class='fa fa-warning txt-color-orangeDark'></i> Warning!",
				content: "Do you really want download the selected objects",
				buttons: '[No][Yes]'
		}, function(ButtonPressed) {
			if (ButtonPressed === "Yes") {
				download_objects(ids);
			}
			if (ButtonPressed === "No") {

			}
		});
    }
    
    
    function download_objects(list){  	
    	document.location.href = '<?php echo site_url('objectmanager/download/object/') ?>/' + list.join('-');

    }
    
    
    
    
    
</script>