<div class="row margin-bottom-10">
	<div class="col-sm-12">		
		<a data-placement="bottom" href="<?php echo site_url('objectmanager/download/file/'.$file->id) ?>" rel="tooltip" data-original-title="Save data on your computer. You can use it in the third party software." style="margin-left:5px;" class="btn btn-info txt-color-white pull-right"><i class="fa fa fa-download"></i>  Download </a>
	
		<?php if($file->print_type == 'additive'): ?>
		<?php $type = strtolower($file->print_type) == 'additive' ? 'print' : 'mill';  ?>
			<a style="margin-left:5px;" rel="tooltip" data-placement="bottom" data-original-title="<?php echo ucfirst($type); ?> this file" href="<?php echo site_url('make/'.$type.'?obj='.$_object_id.'&file='.$file->id)?>" class="btn btn-success pull-right"><i class="fa fa-play rotate-90"></i> <?php echo ucfirst($type); ?></a>
		<?php endif; ?>
		<a style="margin-left:5px;" href="<?php echo site_url('objectmanager/file/view/'.$_object_id.'/'.$file->id) ?>" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> Edit</a>
		<a  href="<?php echo site_url('objectmanager/edit/'.$_object_id)?>" class="btn btn-primary pull-right"> <i class="fa fa-arrow-left"></i> Back to object</a>
	</div>
</div>
<div id="no-webgl" class="alert alert-warning fade in" style="display:none;">
	<button class="close" data-dismiss="alert"></button>
	<i class="fa-fw fa fa-warning"></i>
	Sorry, you need a <strong>WebGL</strong> capable browser to use this. Get the latest <strong>Chrome</strong> or <strong>FireFox</strong>.
</div>

<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php echo $widget; ?>
        </article>
    </div>
</section>