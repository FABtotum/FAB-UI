<div class="modal-header">
	<h6 class="modal-title">Parameters</h6>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-sm-12">
			<div class="well well-sm">
				<div class="input-group">
					<input class="form-control " type="text" id="fa-icon-search" placeholder="Search for a parameter..." >
					<span class="input-group-addon"><i class="fa fa-fw  fa-search"></i></span>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="well well-sm" style="overflow: auto; height: 300px;">
		        <table class="table table-striped table-hover">
		        	
		        	<tbody>
		        		<?php foreach($parameters as $param): ?>
		        			
		        			<tr class="code" data-attr="<?php echo $param['name']; ?>" >
		        				<td width="150px;"><strong><?php echo $param['name']; ?></strong></td>
		        				<td><p class="description"><?php echo $param['desc']; ?></p></td>
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
			    			
			    		var description = $(this).find('.description').html().toLowerCase();
			    		var attr        = $(this).attr('data-attr').toLowerCase();
			    			    		
			    		if((attr.indexOf(search) > -1) || (description.indexOf(search) > -1)){
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