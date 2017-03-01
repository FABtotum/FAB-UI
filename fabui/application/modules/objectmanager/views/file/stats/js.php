<script type="text/javascript">

	var start_date = '<?php echo $start_date; ?>';
	var end_date = '<?php echo $end_date; ?>';	
	var day_data = <?php echo json_encode($statistics); ?>;
	var min_date = '<?php echo date('d/m/Y',strtotime($file->insert_date)) ?>';
	var graph;
	var donut;
	var table;
	var breakpointDefinition = {
			tablet : 1024,
			phone : 480
	};
	var responsiveHelper_dt_basic = undefined;
	
	var colors = new Array();
	colors['stopped']   = 'warning';
	colors['performed'] = 'success';
	colors['deleted']   = 'danger';
	colors['error']     = 'danger';
	
	
	window.Morris.Donut.prototype.setData = function(data, redraw) {
	    if (redraw == null) {
	        redraw = true;
	    }
	    
	    this.data = data;
	    this.values = (function() {
	    var _i, _len, _ref, _results;
	    _ref = this.data;
	    _results = [];
	    
	    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
	        row = _ref[_i];
	        _results.push(parseFloat(row.value));
	    }
	    return _results;
	    }).call(this);
	    this.dirty = true;
	    if (redraw) {
	        return this.redraw();
	    }
	}
	
	
	$(document).ready(function() {
		
		graph = Morris.Line({
			element : 'non-continu-graph',
			data : day_data,
			xkey : 'period',
			ykeys : <?php echo json_encode($status_keys); ?>,
			labels :  <?php echo json_encode($labels); ?>,
			/* custom label formatting with `xLabelFormat` */
			xLabelFormat : function(d) {
				return  d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear();
			},
			/* setting `xLabels` is recommended when using xLabelFormat */
			xLabels : 'day',
			lineColors:<?php echo json_encode($line_colors); ?>,
			lineWidth: 1,
			hideHover: 'auto',
			resize: true,
			fillOpacity: 0.0
		});
		
		donut = Morris.Donut({
			element : 'donut-graph',
			data : <?php echo json_encode($donut_data, JSON_NUMERIC_CHECK ) ?>,
			formatter : function(x) {
				return x + "%"
			},
			colors: <?php echo json_encode($line_colors); ?>,
			resize: true
		});
			
		
		
		var format_date = 'DD/MM/YYYY';
		
		$('#date-picker').daterangepicker({
			format: format_date,
			startDate: start_date,
			endDate: end_date,
			minDate: min_date,
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
           		'Since upload date': [min_date, moment()]
        	}
        
		}, function(start, end) {
			
			$("#date-picker span:first").html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
			$("#graphs-container").css({ opacity: 0.3 });
			start_date = start.format(format_date);
			end_date = end.format(format_date);
			ReloadTable();
			$.ajax({
			  url: "<?php echo site_url('objectmanager/get_json_stats_data/'.$file->id); ?>/" + start + '/' + end,
			  dataType: 'json'
			}).done(function( response ) {
			    set_new_data(response);
			    
			    $("#graphs-container").css({ opacity: 1.0 });
			});
			
			
			
		 });
		 
		 
		 table = $('#table-list').dataTable({
            "aaSorting": [],
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
            "autoWidth": false,
            "bFilter" : false,
            "preDrawCallback" : function() {
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#table-list'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_dt_basic.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_dt_basic.respond();
			},
            "bProcessing": true,
            "sAjaxSource": '<?php echo site_url('objectmanager/get_file_tasks_for_table/'.$file->id) ?>/?start_date=' + start_date + '&end_date=' + end_date,
             "bDeferRender": true,
  			"fnRowCallback": function (row, data, index ){
  				
  				var status = data[3];
  				$('td', row).eq(3).addClass('hidden');
  				$(row).addClass(colors[status]);
  			}
            
       });
	
		
	});
	
	
	function set_new_data(response){
		
		$(".notification").remove();
		$("#graphs-container").show();
		
		if(response.total_tasks == 0){
			$("#graphs-container").hide();
			jQuery(".widget-body-toolbar").find(".col-sm-6:first").html('<div class="alert alert-info animated fadeIn notification"><i class="fa-fw fa fa-info"></i> No data available </div>');
			
			return;
		}
		
		
		graph.setData(response.line);
		donut.setData(response.donut);
		$(".total-tasks").html(response.total_tasks);
		$(".total-duration").html(response.total_duration);
		$(".show-stats").html(response.bars);
		
		
		
	}
	
	function ReloadTable(){
			
		var params = {start_date: start_date, end_date:end_date};
		var url = '<?php echo site_url('objectmanager/get_file_tasks_for_table/'.$file->id) ?>/?' + jQuery.param(params);
		RefreshTable('#table-list', url);
		  	
	}

</script>