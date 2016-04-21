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
			<div class="well well-sm no-padding" style="overflow: auto; height: 300px;">
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
			    	
			    	var desc = $(this).find('.description').html().toLowerCase();
			    	var attr = $(this).attr('data-attr').toLowerCase();
			    	
			    	var search_attr = new RegExp(search);
			    	var search_desc = new RegExp(search);
					 
					if (((m = search_attr.exec(attr)) !== null) || (n = search_desc.exec(desc) !== null)  ) {
					    $(this).show();
					}
				});
		}
		
			
		function show_all() {
			$(".code").show();    
		}
				
		
	});
	
	
</script>
	
</div>