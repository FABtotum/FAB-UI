<?php echo file_header_toolbar($object, $file, 'preview') ?>
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