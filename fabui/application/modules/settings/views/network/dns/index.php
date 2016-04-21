<?php if(isset($message)): ?>
	<div class="row">
		<div class="col-sm-12">
			<div class="alert <?php echo $message['type'] ?> alert-block animated  bounce">
				<?php echo $message['text'] ?>
			</div>
		</div>
	</div>
<?php endif; ?>
<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php echo $widget; ?>
        </article>
    </div>
</section>