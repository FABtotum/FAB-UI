<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"
				aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Filemanager - Select File</h4>
		</div>
		<div class="modal-body">


			<div class="row">
			
				<div class="col-sm-12">
				
				<table class="table  table-bordered">
				
				<?php foreach($_files as $file): ?>
				
					<tr>
						<td><?php echo $file->file_name ?></td>
						<td><a data-id-file="<?php echo $file->id ?>" data-full-path="<?php echo $file ?>"   class="btn btn btn-default select-file"> select <i class="fa fa-share"></i></a></td>
					</tr>
				
				
				<?php endforeach; ?>
				
				</table>
				
				
				</div>
			
			</div>


		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">
				Cancel</button>

		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
