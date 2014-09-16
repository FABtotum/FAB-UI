<div class="row margin-bottom-10">
    
    <div class="col-sm-12">
        
            <!-- <img id="raspi_picture" class="img-responsive rotate-180" src="<?php echo  'http://'.$_SERVER['HTTP_HOST'] ?>/temp/picture.jpg" /> -->
            <img id="raspi_picture" class="img-responsive rotate-180" src="<?php echo widget_url('cam').'media/image.php?time='.time() ?>" />
        
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-sm-8 margin-bottom-10">
        <div class="btn-group btn-group-justified">
            <a data-value="G0 Y+10 F3000" href="javascript:void(0);" class="btn btn-default btn-sm directions "><i class="fa fa-arrow-left"></i></a>
			<a href="javascript:void(0);" class="btn btn-default btn-sm" id="take_photo"><i class="fa fa-camera"></i> Take a pic</a>
			<a data-value="G0 Y-10 F3000" href="javascript:void(0);" class="btn btn-default btn-sm directions "><i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="btn-group btn-group-justified">
            <a  href="<?php echo widget_url('cam').'views/download.php' ?>" class="btn btn-default btn-sm"><i class="fa fa-download"></i></a>
        </div>
    </div>
</div>




