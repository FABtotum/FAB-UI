<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-puzzle-piece fa-fw "></i> Objectmanager
		</h1>
	</div>
	<div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 text-align-right">
		<div class="page-title">
			<a href="<?php  echo site_url('objectmanager/add')?>"
				class="btn btn-default"> <i class="fa fa-plus"></i> Add new object
			</a>
		</div>
	</div>
</div>

<div class="row">
	
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">

		<p>Total Objects: <span id="num_files"><?php echo count($_objects); ?></span></p>
	</div>
	
	
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	
		<div class="row" id="files-container">
			<?php foreach ($_objects as $obj): ?>

			<div id="obj<?php echo $obj->id; ?>"
				class="col-xs-12 col-sm-3 col-md-3 col-lg-3 box">

				<div class="file">
					<div class="file-header">
						<div
							class="btn-group display-inline pull-right  text-align-right ">
							<button class="btn  btn-default dropdown-toggle"
								data-toggle="dropdown">
								<i class="fa fa-cog fa-lg"></i>
							</button>
							<ul class="dropdown-menu dropdown-menu-xs pull-right">
								<li><a
									href="<?php  echo site_url('objectmanager/edit/'.$obj->id)?>"
									class="edit"><i
										class="fa fa-pencil fa-lg fa-fw txt-color-greenLight"></i> <u>E</u>dit</a>
								</li>
								<li><a href="javascript:void(0);" class="file-delete"
									file-id="<?php echo $obj->id; ?>"
									file-name="<?php echo $obj->obj_name; ?>"><i
										class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>
								</li>
								<li class="divider"></li>
								<li class="text-align-center"><a href="javascript:void(0);">Cancel</a>
								</li>
							</ul>
						</div>

					</div>
					<div class="file-body">
						<p>
							name:
							<?php echo $obj->obj_name; ?>
						</p>
						<p>
							description: <?php echo $obj->obj_description; ?>
						</p>
					</div>
					<div class="file-footer"></div>
				</div>
			</div>

			<?php endforeach; ?>

		</div>
	</div>
</div>
