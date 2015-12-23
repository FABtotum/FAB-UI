<script type="text/javascript">
	
	var oTable;
	var aData;
	
	$(function() {
		oTable = $("#configs-table").dataTable({
			"bProcessing": true,
			"sAjaxSource": '<?php echo module_url('settings').'ajax/eeprom_configs.php' ?>',
			"fnRowCallback": function ( row, data, index ){
  				$('td', row).eq(0).addClass('hidden');
  				$('td', row).eq(1).addClass('hidden');
  				$('td', row).eq(2).attr('width', '20px');
  			},
  			"fnDrawCallback" : tableCallBack
		});
		
		
		$("#safe-config").on('click', save_config);
		
		function tableCallBack(){
			
			$("#configs-table a i[data-toggle='row-detail']").on("click", function() {
				var nTr = $(this).parents("tr")[0];
				if (oTable.fnIsOpen(nTr)) {
					/* This row is already open - close it */
					$(this).removeClass("fa-chevron-down").addClass("fa-chevron-right");
					this.title = "Show Details";
					oTable.fnClose(nTr);
				} else {
					/* Open this row */
					$(this).removeClass("fa-chevron-right").addClass("fa-chevron-down");
					this.title = "Hide Details";
					oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr), "details");
				}
				return false;
			});
			
			
		}
		
		function fnFormatDetails(oTable, nTr) {
			aData = oTable.fnGetData(nTr);
			
			var configId    = aData[0];
			var eepromValues = jQuery.parseJSON(aData[1]);
			
			
			var innerHtml = '<div class="well well-sm">';
			
			innerHtml = '<div class="row"><div class="col-sm-12">';
			
			$.each(eepromValues, function(key,value) {
			 	console.log(key+':'+value.comment);
			 	
			 	innerHtml += '<p><strong>' + value.comment + '</strong></br>';
			 	innerHtml +=  value.command + '</p>';
			 	
			});
			
			innerHtml += '</div></div>';
			
			var edit_button      = '<a rel="tooltip" data-placement="bottom" data-original-title="Edit the file" href="javascript:openModal();" class="btn btn-primary details-button"><i class="fa fa-pencil"></i> Edit</a>';
			var delete_button    = '<a rel="tooltip" data-placement="bottom" data-original-title="Delete the file" href=""" class="btn btn-danger details-button delete-file"><i class="fa fa-trash"></i> Delete</a>';
			var duplicate_button = '<a rel="tooltip" data-placement="bottom" data-original-title="Edit the file" href="" class="btn btn-primary details-button"><i class="fa fa-pencil"></i> Duplicate</a>';;
			
			innerHtml += '<div class="row"><div class="col-sm-12">'+edit_button+duplicate_button+delete_button+'</div></div>';
			
			innerHtml += '</div>';
			
			return innerHtml;
		}		
		
	});
	
	function openModal(){
		
		$(".eeprom_command").remove();
		$("#config_name").val(aData[4]);
		$("#config_description").val(aData[5]);
		
		var eepromValues = jQuery.parseJSON(aData[1]);
		
		var fieldSetHtml = '<hr class="margin-bottom-10">';
		
		$.each(eepromValues, function(key,value) {
			
			fieldSetHtml += '<section class="eeprom_command">';
			fieldSetHtml += '<label class="label comment_'+key+'">' + value.comment + '</label>';
			fieldSetHtml += '<label class="input"><input name="command_'+key+'" id="command_'+key+'" class="input-sm" type="text" value="'+ value.command +'" /></label>';
			fieldSetHtml += '</section>';

		});
		
		$("#values").append(fieldSetHtml);
		$('#editModal').modal('show');
	}
	
	
	function save_config(){
		
		$eeprom = $('.eeprom_command');
		var eeprom_data = [];
		
		$eeprom.each(function(){	
			var item = {'comment' : $(this).find('.label').html(), 'command' : $(this).find('input:text').val() };
			eeprom_data.push(item);
		});
		
		$.ajax({
    		type: 'POST',
    		url : '<?php echo module_url('settings').'ajax/eeprom.php' ?>',
    		data: {id:aData[0], name: $("#config_name").val(), description: $("#config_description").val(), eeprom: eeprom_data, action:'save'},
    		dataType: 'json'
    	}).done(function (response) {
    		
    	});
		
		
		
	}
	
</script>