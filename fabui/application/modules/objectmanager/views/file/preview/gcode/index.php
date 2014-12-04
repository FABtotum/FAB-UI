<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark"> 
			<i class="icon-fab-manager fab-fw"></i> Objectmanager <span> > File > Preview GCode</span>
		</h1>
	</div>
	<div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 text-align-right">
		<div class="page-title">
			<a href="<?php  echo site_url('create?obj='.$_object_id.'&file='.$file->id)?>" class="btn btn-primary"> <i class="fab-lg fab-fw icon-fab-print"></i> Print</a>
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