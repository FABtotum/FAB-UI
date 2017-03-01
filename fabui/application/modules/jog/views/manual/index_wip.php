<div class="modal-header">
	<h4 class="modal-title">Implemented and supported GCodes</h4>
</div>
<div class="modal-body">
	
	
	<div class="row">
		
		<div class="col-sm-12">
			<div class="well well-sm">
				<div class="input-group">
					<input class="form-control input-lg" type="text" id="fa-icon-search" placeholder="Search for a code..." >
					<span class="input-group-addon"><i class="fa fa-fw fa-lg fa-search"></i></span>
				</div>
			</div>
		</div>
		
	</div>
	
	<div class="row">
		
		<div class="col-sm-12">
			
			<div class="well well-sm" style="overflow: auto">
				<ul class="list-unstyled">
					<?php echo $codes; ?>
		          </ul>
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
					hide_divs(search.toUpperCase());
				}
			});
			
			
			
			function hide_divs(search) {
				
			    $(".code").hide(); 

			    $(".code").each(function(index, value) {
			    	
			    	if(typeof $(this).attr('data-attr') !== typeof undefined && $(this).attr('data-attr') !== false){			    		
			    		if($(this).attr('data-attr').indexOf(search) > -1 ){
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