<?php if(isset($_REQUEST['ip_changed'])): ?>
	<div class="alert alert-info animated  fadeIn" role="alert">
		<i class="fa fa-check"></i> Well done! New IP address saved
	</div>
<?php endif; ?>
<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php echo $widget; ?>
        </article>
    </div>
</section>