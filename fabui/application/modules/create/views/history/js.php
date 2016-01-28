<script type="text/javascript">
	
	var oTable;
	var start_date = '<?php echo $start_date; ?>';
	var end_date = '<?php echo $end_date; ?>';
	var base_url = '<?php echo site_url('make/history') ?>';
	
	
	$(function() {
		
		
		$("#history tbody > tr").on("click", function(e) {
		
			var tag = e.target.nodeName;
			
			if(tag == 'TD' || tag == "I"){
			
				var nTr = $(this);
				
				var aCell = $(this).find("a i[data-toggle='row-detail']");
				
				
				
				
				if (oTable.fnIsOpen(nTr)) {
					/* This row is already open - close it */
					aCell.removeClass("fa-chevron-down").addClass("fa-chevron-right");
					this.title = "Show Details";
					nTr.removeClass("shown");
					oTable.fnClose(nTr);
					$("[rel=tooltip], [data-rel=tooltip]").tooltip();
				} else {
					/* Open this row */
					nTr.addClass("shown");
					aCell.removeClass("fa-chevron-right").addClass("fa-chevron-down");
					oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr), "details");
					$("[rel=tooltip], [data-rel=tooltip]").tooltip();
				}
				return false;
			
			}
			
			
		});
		
		
		oTable = $("#history").dataTable({
			"aaSorting": []
		});
		
		
		var format_date = 'DD/MM/YYYY';
		
		$('input[name=date-range-picker]').daterangepicker({
			format: format_date,
			 startDate: start_date,
			 endDate: end_date,
			 ranges: {
           		'Today': [moment(), moment()],
           		'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           		'Last 7 days': [moment().subtract(6, 'days'), moment()],
           		'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           		'This month': [moment().startOf('month'), moment().endOf('month')],
           		'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        	}
        
		}, function(start, end) {
			start_date = start.format(format_date);
			end_date = end.format(format_date);
			/*reload_page();*/
		 });
		 
		 $("#search").on('click', reload_page);
		
		function fnFormatDetails(oTable, nTr) {
			var aData = oTable.fnGetData(nTr);
			
			var start_date = aData[6];
			var finish_date = aData[7];
			var note =aData[8];
			var type =aData[9];
			var id_file = aData[10];
			var id_object = aData[11];
			
			var table = '<table style="margin-bottom:1px !important;" cellpadding="5" cellspacing="0" border="0" class="table table-hover table-condensed">';
			
			table += '<tr><td width="100px">Started </td><td>'+start_date +'</td></tr>';
			table += '<tr><td width="100px">Finished </td><td>'+finish_date +'</td></tr>';
			
			if(note != ''){
				table += '<tr><td width="100px">Note </td><td><p>'+note +'</p></td></tr>';
			}
			
			if(type == 'print' || type == 'mill'){
				
				
				var action_url = '/fabui/make/'+type+'?obj='+id_object+'&file='+id_file;
				var action_button = '<a class="btn btn-primary" href="'+action_url+'"> ' + type[0].toUpperCase() + type.slice(1) +' it again</a>';
				
				table += '<tr style="border:0px;">';
				table += '<td width="100px"></td><td>' + action_button +'</td>';
				table += '</tr>';
			}
			
			
			table += '</table>';
			
			return table;
		}
		
		function reload_page(){
			
			var type = $("#type").val();
			var status   = $("#status").val();
			
			var params = {start_date: start_date, end_date:end_date, type:type, status:status};
			
			base_url += '?' + jQuery.param(params);
			document.location.href = base_url;
		}
		
		
	});

</script>