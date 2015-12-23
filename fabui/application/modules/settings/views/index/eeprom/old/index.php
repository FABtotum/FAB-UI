<div class="tab-pane animate fadeIn fade in active" id="tab2">
	<div class="row">
		<div class="col-sm-12">
			<table id="configs-table" class="table table-striped table-bordered ">
				<thead>
					<tr>
						<th class="hidden"></th>
						<th class="hidden"></th>
						<th class="center" width="20px"></th>
						<th>Active</th>
						<th>Name</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-body">
      			<div class="smart-form">
      				<fieldset id="values">
      					<div class="row">
	      					<section class="col col-6">
								<label class="label">Name</label>
								<label class="input">
									<input class="input-sm" type="text" maxlength="10" id="config_name" name="config_name">
								</label>
							</section>
							<section class="col col-6">
								<label class="label">Description</label>
								<label class="textarea">
									<textarea id="config_description"></textarea>
								</label>
							</section>
						</div>
      				</fieldset>
      				
      			</div>
      		</div>
		   	<div class="modal-footer">
		    	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		    	<button type="button" class="btn btn-primary" id="safe-config">Save changes</button>
		    </div>
		 </div>
  </div>
</div>
