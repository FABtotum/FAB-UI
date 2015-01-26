<div class="modal-header">
	<h4 class="modal-title">Implemented and supported GCodes</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-sm-12">
			<div class="well well-sm">
				<div class="input-group">
					<input class="form-control " type="text" id="fa-icon-search" placeholder="Search for a code..." >
					<span class="input-group-addon"><i class="fa fa-fw  fa-search"></i></span>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="well well-sm" style="overflow: auto; height: 300px;">
		        <table class="table table-striped table-hover">
		        	<thead>
		        		<tr>
		        			<th>Code</th>
		        			<th>Description</th>
		        		</tr>
		        	</thead>
		        	<tbody>
		        		<?php foreach($gcodes as $code): ?>
		        			<tr class="code" data-attr="<?php echo $code['type'].$code['code']; ?>" >
		        				<td width="150px;"><strong><?php echo $code['label']; ?></strong></td>
		        				<td><p class="description"><?php echo $code['description']; ?></p></td>
		        			</tr>
		        		<?php endforeach; ?>
		        		<?php foreach($mcodes as $code): ?>
		        			<tr class="code" data-attr="<?php echo $code['type'].$code['code']; ?>" >
		        				<td width="150px;"><strong><?php echo $code['label']; ?></strong></td>
		        				<td><p class="description"><?php echo $code['description']; ?></p></td>
		        			</tr>
		        		<?php endforeach; ?>
		        	</tbody>
		        </table>
			</div>
		</div>	
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		Close
	</button>
<script type="text/javascript">

	$(function() { 
			$("#fa-icon-search").keyup(function() {
				var search = $.trim(this.value);
				
				if (search === "") {
					show_all();
				}
				else {
					hide_divs(search.toLowerCase());
				}
			});
			
			
			function hide_divs(search) {
				
				
			    $(".code").hide(); 

			    $(".code").each(function(index, value) {
			    	
			    	if(typeof $(this).attr('data-attr') !== typeof undefined && $(this).attr('data-attr') !== false){
			    		
			    		var desc = $(this).find('.description').html().toLowerCase();
			    		var attr = $(this).attr('data-attr');
			    							    		
			    		if((attr.indexOf(search) > -1) || (desc.indexOf(search) > -1)){
			    			$(this).show();
			    		}
			    	}

				});
		}
		
			
		function show_all() {
			$(".code").show();    
		}
				
		
	});
	
	
</script>
	
</div>