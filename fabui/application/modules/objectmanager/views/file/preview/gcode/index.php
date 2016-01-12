<div class="row">
	<div class="col-sm-12 text-align-right">
		<div class="page-title">
			<a href="<?php  echo site_url('objectmanager/edit/'.$_object_id)?>" class="btn btn-primary"> <i class="fa fa-arrow-left"></i> Back to object</a>
			<a href="<?php  echo site_url('make/print?obj='.$_object_id.'&file='.$file->id)?>" class="btn btn-primary"> <i class="fa fa-play rotate-90"></i> Print</a>
  		</div>
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