<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa fa-life-ring"></i> Support & Frequently Asked Questions </h1>
	</div>
</div>

<div class="row">
	<div class="col-sm-4">
		<div class="well text-center">
			<i class="fa fa-life-ring fa-2x"></i>
			<h3><a href="<?php echo $support_url ?>" target="_blank">Online support</a></h3>
			<p class="font-md">Need help? Access the support system</p>
		</div>
	</div>
	
	<div class="col-sm-4">
		<div class="well text-center">
			<i class="fa fa-book fa-2x"></i>
			<h3><a href="<?php echo $manual_url ?>" target="_blank">Manual</a></h3>
			<p class="font-md">Download the latest manuals</p>
		</div>
	</div>
	
	<div class="col-sm-4">
		<div class="well text-center">
			<i class="fa fa-puzzle-piece fa-2x"></i>
			<h3><a href="<?php echo $wiki_url ?>" target="_blank">Wiki</a></h3>
			<p class="font-md">Access the FABtotum Wiki</p>
		</div>
	</div>
</div>

<div class="row">
	
	<div class="col-sm-12">
		<p class="font-md"><i class="fa fa-users"></i> In the <a target="_blank" href="<?php echo $forum_url; ?>"><u>forums</u></a> you will find interesting people, their projects and their expertise, and possibly ideas on how to use your <strong>FABtotum</strong> and what to do with it</p>
		<p class="font-md"><i class="fa fa-comments-o"></i> Check out our development <a target="_blank" href="<?php echo $blog_url; ?>"><u>blog</u></a> for the latest updates and behind	the scenes of our development process.</p>
	</div>
		
</div>

<?php if(isset($no_faq)): ?>
	
	<div class="row">
		<div class="alert alert-warning fade in">
			<i class="fa-fw fa fa-warning"></i>
			<strong>Warning </strong> FAQs not avaiables. Please check internet connectivity, <a href="<?php echo site_url("settings/network") ?>">reconnect</a> and try again
		</div>
	</div>
	
<?php else: ?>
	
	<div class="row">
		<h2 class="row-seperator-header"><i class="fa fa-question-circle "></i> FAQs </h2>
	</div>
	
	<?php $count_group = 1; ?>
	<?php foreach($faq as $group): ?>
	<div class="row">
		<div class="col-sm-12">
			<h6 class=""><i class="fa fa-info-circle"></i> <?php echo $group['title']; ?></h6>
			<div class="panel-group smart-accordion-default" id="accordion_<?php echo $count_group; ?>">
				
				<?php $count_item = 1; ?>
				<?php foreach($group['faq'] as $item): ?>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_<?php echo $count_group; ?>" href="#collapse_<?php echo $count_group.'_'.$count_item ?>" class="collapsed"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> <?php echo $item['question'] ?> </a></h4>
					</div>
					<div id="collapse_<?php echo $count_group.'_'.$count_item ?>" class="panel-collapse collapse">
						<div class="panel-body">
							<p><?php echo $item['answer']; ?></p>
						</div>
					</div>
				</div>
				<?php $count_item++; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php $count_group++; ?>
	<?php endforeach; ?>
<?php endif; ?>