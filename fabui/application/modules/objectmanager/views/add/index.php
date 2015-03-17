<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="icon-fab-manager fab-fw"></i> Objectmanager <span> > Add new</span>

		</h1>
	</div>
	<div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 text-align-right">
		<div class="page-title">
			<a href="<?php  echo site_url('objectmanager')?>"
				class="btn btn-primary"> <i class="icon-fab-manager"></i> Back to objects
			</a>
		</div>
	</div>
</div>


<div class="row">

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<div class="jarviswidget arviswidget-color-blueLight" id="wid-id-3"
			data-widget-editbutton="false" data-widget-deletebutton="false"
			data-widget-fullscreenbutton="false" data-widget-togglebutton="false"
			data-widget-sortable="false" data-widget-colorbutton="false">
			<header>
				<span class="widget-icon"> <i class="fa fa-plus"></i>
				</span>
				<h2>Add new object</h2>
			</header>

			<div>

				<div class="jarviswidget-editbox"></div>

				<div class="widget-body">

					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<form class="" id="object-form"
								action="<?php echo site_url('objectmanager/add') ?>"
								method="POST">
								<fieldset>
									<input name="authenticity_token" type="hidden">
									<div class="checkbox">
										
										<label rel="tooltip" data-placement="top" data-original-title="If is checked everyone can use this object">
												<input    type="checkbox" name="private" id="private"> Public
										</label>
									</div>
									<div class="form-group">
										<label>Name</label> <input class="form-control" id="name"
											name="name" type="text">
									</div>

									<div class="form-group">
										<label>Description</label>
										<textarea name="description" class="form-control" rows="5"></textarea>
									</div>
									<input type="hidden" id="files" name="files">
                                    <input type="hidden" id="usb_files" name="usb_files">
								</fieldset>
							</form>
						</div>


						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							
                            <ul id="myTab1" class="nav nav-tabs bordered">
                                <li class="active"><a href="#remote" data-toggle="tab">  Local Disk</a></li>
                                <li><a href="#usb" class="check-usb" data-toggle="tab"> Usb Disk</a></li>
                            </ul>
                            <div id="myTabContent1" class="tab-content padding-10">
                                <!-- dropzone -->
                                <div class="tab-pane fade in active" id="remote">
                                     <div>
        								<form enctype="multipart/form-data"
        									action="<?php echo site_url('filemanager/upload'); ?>"
        									class="dropzone" id="mydropzone"></form>
        							</div>
                                </div>
                                
                                <!-- usb disk -->
                                <div class="tab-pane fade in" id="usb">
                                
                                   
                                </div>
                            
                            </div>
                            
                            
                           
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-actions">
								<button class="btn btn-primary btn-lg" id="save-object">
									<i class="fa fa-save"></i> Save
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



