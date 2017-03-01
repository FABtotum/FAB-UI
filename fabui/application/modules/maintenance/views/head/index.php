<style>.jumbotron{padding:20px;} .jumbotron p {font-size: 15px;} </style>
<div class="row">
	<div class="col-sm-12 alerts-container">
<?php if(!isset($units['hardware']['head']['type']) || $units['hardware']['head']['type'] == ''): ?>
		<div class="alert alert-warning animated  fadeIn" role="alert">
			<i class="fa fa-warning"></i><strong>Warning</strong> Seems that you still have not set the head your are using.
		</div>
<?php else: ?>
		<div class="alert alert-info animated  fadeIn" role="alert">
			<i class="fa fa-info-circle"></i> Currently  your <strong>FABtotum Personal Fabricator</strong> is configured to use <strong><?php echo  $units['hardware']['head']['description']; ?></strong>
		</div>
<?php endif; ?>
	</div>
</div>

<div class="row">
	<section class="col-sm-12">
		<?php echo $widget; ?>
	</section>
</div>


<div class="row hidden">
	<?php foreach($heads_descriptions as $name => $val): ?>
		<div id="<?php echo $name ?>_description">
			<p class="margin-bottom-10"><?php echo $val['desc']; ?></p>
			<?php if($val['more'] != ''): ?>
			<a style="padding: 6px 12px;" target="_blank" href="<?php echo $val['more']; ?>" class="btn btn-default ">More details</a>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
