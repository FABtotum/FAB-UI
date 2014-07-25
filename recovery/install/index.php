<?php 
include( "inc/header.php"); 
?>
	<div id="main" role="main">
		<!-- MAIN CONTENT -->
		<div>
			<form method="POST" id="wizard-1" novalidate="novalidate" class="lockscreen animated flipInY" action="install.php">
				<div class="logo text-align-center">
					<img src="/assets/img/logo_fabtotum.png" />
				</div>
				<div>
					<div class="row">
						<div class="col-sm-12">
							<div id="bootstrap-wizard-1" class="col-sm-12">
								<div class="form-bootstrapWizard">
									<ul class="bootstrapWizard form-wizard">
										<li class="active" data-target="#step1">
											<a href="#tab1" data-toggle="tab"> <span class="step">1</span> <span
											class="title">Welcome</span>
											</a>
										</li>
										<li data-target="#step2">
											<a href="#tab2" data-toggle="tab"> <span
											class="step">2</span> <span class="title">Account</span>
											</a>
										</li>
										<li data-target="#step3">
											<a href="#tab3" data-toggle="tab"> <span
											class="step">3</span> <span class="title">Network</span>
											</a>
										</li>
										<li data-target="#step4">
											<a href="#tab4" data-toggle="tab"> <span
											class="step">4</span> <span class="title">Finish</span>
											</a>
										</li>
									</ul>
									<div class="clearfix">
									</div>
								</div>
								<div class="tab-content">
									<?php include( 'step1.php') ?>
									<?php include( 'step2.php') ?>
									<?php include( 'step3.php') ?>
									<?php include( 'step4.php') ?>
									<div class="form-actions">
										<div class="row">
											<div class="col-sm-12">
												<ul class="pager wizard no-margin">
													<li class="previous disabled">
														<a href="javascript:void(0);" class="btn btn-lg btn-default"> Previous </a>
													</li>
													<li class="next">
														<a href="javascript:void(0);" class="btn btn-lg txt-color-darken"> Next </a>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php 

include( "inc/footer.php"); 
   
    
?>