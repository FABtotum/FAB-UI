<div class="row margin-bottom-10">
	<div class="col-sm-12">
		<h1>How can we help <span class="semi-bold">You</span>?</h1>
		<select class="form-control input-lg" id="help">
			<option value="0">Select topic</option>
			<?php foreach($support_faq as $support): ?>
			<option value="<?php echo $support['id'] ?>"><?php echo $support['desc_en'] ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>

<div class="row margin-bottom-10" id="faq-content"></div>

<div class="row margin-bottom-10">
	<div class="col-sm-4">
		<div class="well  well-light">
			<h2 class="text-center"><a style="cursor:pointer;" target="_blank" href="http://wiki.fabtotum.com/doku.php"><i class="fa fa-wikipedia-w fa-border fa-2x"></i></a></h2>
			<p class="text-center">Access the FABtotum Wiki</p>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="well  well-light">
			<h2 class="text-center"><a style="cursor:pointer;" target="_blank" href="http://support.fabtotum.com/manual_eng.pdf"><i class="fa fa-book fa-border fa-2x"></i></a></h2>
			<p class="text-center">First Setup</p>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="well  well-light">
			<h2 class="text-center"><a style="cursor:pointer;" target="_blank" href="https://github.com/FABtotum/FAB_Configs/archive/master.zip"><i class="fa fa-cog fa-border fa-2x"></i></a></h2>
			<p class="text-center">Download the latest slicing configurations</p>
		</div>
	</div>
</div>

<div class="row margin-bottom-10">
	<div class="col-sm-12">
		<div class="well  well-light">
			<h2 class="text-center"><a style="cursor:pointer;" target="_blank" href="http://forum.fabtotum.com/"><i class="fa fa-comments fa-border fa-2x"></i></a></h2>
			<h2 class="text-center">Support Communities</h2>
			<p class="text-center">Find and share solutions with fellow Fabtotum users around the world</p>
			<p class="text-center"><a href="http://forum.fabtotum.com/" target="_blank">Join the conversation</a></p>
		</div>
	</div>
</div>

<div class="row" id="faq-0"></div>
<?php foreach($support_faq as $support): ?>
<div class="row faq hidden " id="faq-<?php echo $support['id'] ?>">	
	<div class="col-sm-12">
		<div class="panel-group smart-accordion-default" id="accordion-<?php echo  $support['id']; ?>">
			<?php foreach($support['faq'] as $faq): ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#<?php $support['id']; ?>" href="#answer-<?php echo $faq['id'] ?>" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> <?php echo $faq['question_en'] ?> </a>
					</h4>
				</div>
				<div id="answer-<?php echo $faq['id'] ?>" class="panel-collapse collapse">
					<div class="panel-body">
						<?php echo $faq['answer_en']; ?>
						<?php if($faq['support'] == 1): ?>
						<a target="_blank" href="http://www.fabtotum.com/tickets" class="btn btn-sm btn-default"><strong>Contact support for this issue</strong></a>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<hr>
<?php endforeach; ?>