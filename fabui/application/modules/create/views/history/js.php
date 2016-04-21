<script type="text/javascript">
	
	var responsiveHelper_dt_basic = undefined;
	var oTable;
	var start_date = '<?php echo $start_date; ?>';
	var end_date = '<?php echo $end_date; ?>';
	var base_url = '<?php echo site_url('make/history') ?>';
	var min_date = '<?php echo $min_date; ?>';
	
	var breakpointDefinition = {
			tablet : 1024,
			phone : 480
	};
	
	var type;
	var status;
	
	
	$(function() {
		
		$(".dropdown-menu > li > a").on('click', function(e){
			
			var title = $(this).html();
			var dataType = $(this).attr('data-type');
			var dataValue = $(this).attr('data-value');
			
			switch(dataType){
				case 'type':
					type = dataValue;
					break;
				case 'status':
					status = dataValue;
					break;
			}
			
			$(this).parent().parent().parent().find('button:first').find('span:first').html(title);
			ReloadTable();
			
			if($("#s2").is(":visible")) ReloadStats();
			
		});
		
		
		oTable = $("#history").dataTable({
			"aaSorting": [],
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"autoWidth": false,
			"preDrawCallback" : function() {
					if (!responsiveHelper_dt_basic) {
						responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#history'), breakpointDefinition);
					}
			},
			"rowCallback" : function(nRow) {
					responsiveHelper_dt_basic.createExpandIcon(nRow);
			},
				"drawCallback" : function(oSettings) {
					responsiveHelper_dt_basic.respond();
			},
			"sAjaxSource": '<?php echo site_url('create/history_table_data')?>', 
			"fnRowCallback": function (row, data, index ){
				$('td', row).eq(0).addClass('center').css('width', '20px'); //detail button
				$('td', row).eq(1).css('width', '100px'); //when
				$('td', row).eq(2).css('width', '80px'); //make
				$('td', row).eq(3).css('width', '100px'); //status
				//4 description
				$('td', row).eq(5).css('width', '100px').addClass('center'); //time
				$('td', row).eq(6).addClass('hidden');
				$('td', row).eq(7).addClass('hidden');
				$('td', row).eq(8).addClass('hidden');
				$('td', row).eq(9).addClass('hidden');
				$('td', row).eq(10).addClass('hidden');
				$('td', row).eq(11).addClass('hidden');
			},
			"fnDrawCallback" : fnCallBack,
			
		});
		
		$("#stats-click").on('click', ReloadStats);
		
		var format_date = 'DD/MM/YYYY';
	
		 
		 $('#date-picker').daterangepicker({
			format: format_date,
			startDate: start_date,
			endDate: end_date,
			locale: {
      			format: format_date
   			},
			ranges: {
           		'Today': [moment(), moment()],
           		'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           		'Last 7 days': [moment().subtract(6, 'days'), moment()],
           		'Last 30 Days': [moment().subtract(30, 'days'), moment()],
           		'This month': [moment().startOf('month'), moment().endOf('month')],
           		'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
           		'Since beginning': [min_date, moment()]
        	}
        
		}, function(start, end) {
			
			start_date = start.format(format_date);
			end_date = end.format(format_date);
			
			$("#date-picker span:first").html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
			
			ReloadTable();
			if($("#s2").is(":visible")) ReloadStats();
		 });
		 
		 
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
				var action_button = '<a class="btn btn-xs btn-default" href="'+action_url+'"><i class="fa fa-play fa-rotate-90"></i> ' + type[0].toUpperCase() + type.slice(1) +' it again</a>';
				var stats_button = '<a style="margin-left:5px;" class="btn btn-xs btn-default" href="/fabui/objectmanager/file/stats/'+id_object+'/'+id_file+'"><i class="fa fa-area-chart"></i> Stats</a>';
				
				table += '<tr style="border:0px;">';
				table += '<td width="100px"></td><td>' + action_button + stats_button + '</td>';
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
		
		
		function fnCallBack(){
			
			$("#history tbody > tr").on("click", function(e) {
		
				var tag = e.target.nodeName;
				
				if(tag == 'TD' || tag == "I" || tag == 'H4'){
					
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
			
			
		}
		
		
		function ReloadTable(){
			
		  	var params = {start_date: start_date, end_date:end_date, type:type, status: status};
		  	var url = '<?php echo site_url('create/history_table_data') ?>?' + jQuery.param(params);
		  	RefreshTable('#history', url);
		  	
		}
		
		function ReloadStats() {
			$("#s2").css({ opacity: 0.3 });
			var params = {start_date: start_date, end_date:end_date, type:type, status: status};
		  	var url = '<?php echo site_url('create/history_stats_data') ?>?' + jQuery.param(params);
		  	
		  	$.get(url, null, function(html){
		  		
		  		$("#s2").html(html);
		  		$("#s2").css({ opacity: 1 });
		  		
		  	})
		}
		
		
		function RefreshTable(tableId, urlData)
		{
		 $("#history_wrapper").css({ opacity: 0.3 });	
		  $.getJSON(urlData, null, function( json )
		  {
		    table = $(tableId).dataTable();
		    oSettings = table.fnSettings();
		
		    table.fnClearTable(this);
		
		    for (var i=0; i<json.aaData.length; i++)
		    {
		      table.oApi._fnAddData(oSettings, json.aaData[i]);
		    }
		
		    oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
		    table.fnDraw();
		    $("#history_wrapper").css({ opacity: 1 });
		    
		  });
		}
		
		
		
	});

</script>