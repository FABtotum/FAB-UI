<?php  
require_once("inc/init.php");
require_once("inc/utilities.php");
//include header
include("inc/header.php");


$_myfab_local_version   = myfab_get_local_version();
$_myfab_remote_version  = myfab_get_remote_version();



$_marlin_local_version  = marlin_get_local_version();
$_marlin_remote_version = marlin_get_remote_version();

?>
<div id="main" role="main">
	<!-- MAIN CONTENT -->
	<div id="content" class="container">
        <div class="row animated fadeIn">
            <div class="col-sm-12">
                <h1 class="txt-color-red login-header-big text-center">UPDATE CENTER</h1>
            </div>
        </div>
		<div class="row animated fadeIn">
            
			<div class="col-sm-12">
				<div class="well ">
					<table class="table table-striped table-forum table-hover">

						<tbody>
							<!-- MYFAB  -->
							<tr
								class="<?php echo $_myfab_local_version < $_myfab_remote_version ? 'warning' : ''; ?>">
								<td style="width: 40px;"><i
									class="fa fa-tablet fa-2x text-muted"
									style="position: relative;"> <?php if($_myfab_local_version < $_myfab_remote_version): ?>
										<em class="notifica">!</em> <?php endif; ?>
								</i>
								</td>
								<td>
									<h4>
										<a href="javascript:void(0);">myFab</a> <small>current
											version: <?php echo $_myfab_local_version ?>
										</small>
									</h4>

								</td>
								<td class="hidden-xs"><?php if($_myfab_local_version < $_myfab_remote_version): ?>
									<h5>A new update of myFab is avaiable!</h5> <small>new version
										is <?php echo $_myfab_remote_version ?> | <a
										href="javascript:void(0)">see details</a>
								</small> <?php else: ?>
									<h5>You have the latest version</h5> <?php endif; ?>
								</td>
								<td class="text-center"><?php if(myfab_get_local_version() < myfab_get_remote_version()): ?>
									<a class="btn btn-default btn-sm download download-myfab"
									download-version="<?php echo myfab_get_remote_version() ?>"
									download-item="myfab" href="javascript:void(0);"><i
										class="fa fa-download"></i> Update</a> <?php else: ?> <?php endif; ?>
								</td>
							</tr>
							<!-- MARLIN -->
							<tr
								class="<?php echo $_marlin_local_version < $_marlin_remote_version ? 'warning' : ''; ?>">

								<td style="width: 40px;"><i
									class="fa fa-shield fa-2x text-muted"
									style="position: relative;"> <?php if($_marlin_local_version < $_marlin_remote_version): ?>
										<em class="notifica">!</em> <?php endif; ?>
								</i>
								</td>
								<td><h4>
										<a href="javascript:void(0)">Marlin</a><small>current version:
											<?php echo $_marlin_local_version ?>
										</small>
									</h4></td>
								<td class="hidden-xs"><?php if($_marlin_local_version < $_marlin_remote_version): ?>
									<h5>A new update of Marlin is avaiable!</h5> <small>new version
										is <?php echo $_marlin_remote_version ?> | <a
										href="javascript:void(0)">see details</a>
								</small> <?php else: ?>
									<h5>You have the latest version of Marlin</h5> <?php endif; ?>
								</td>
								<td class="text-center"><?php if($_marlin_local_version < $_marlin_remote_version): ?>
									<a class="btn btn-default btn-sm download"
									download-version="<?php echo $_marlin_remote_version ?>"
									download-item="marlin" href="javascript:void(0);"><i
										class="fa fa-download"></i> Update</a> <?php else: ?> <?php endif; ?>
								</td>
							</tr>
						</tbody>
					</table>





				</div>



			</div>

		</div>


        <div class="row progress-container" style="display: none;">
            <div class="col-sm-12">
                <div class="well">
                    <p>
						<span id="status"></span> <span id="velocita"></span> <span id="percentuale" class="pull-right"></span>
					</p>
                    <div class="progress">
						<div id="progress-download" class="progress-bar bg-color-blue"
							role="progressbar" style="width: 0%"></div>
					</div>
                </div>
            </div>
        </div>
        
        
        
        
        <!-- 
		<div class="row progress-container" style="display: none;">
			<div class="col-sm-12">
				<div class="well">
					<hr class="simple">
					<p class="download-container " style="display: none;">
						<span id="status"></span> <span id="velocita"></span> <span
							id="percentuale" class="pull-right"> </span>
					</p>
					<div class="progress download-container" style="display: none;">
						<div id="progress-download" class="progress-bar bg-color-blue"
							role="progressbar" style="width: 0%"></div>
					</div>

					<hr class="simple">

					<p class="extract-container hide">
						<span id="extract-status"></span> <span id="extract-percentuale"
							class="pull-right"> </span>

					</p>

					<div class="progress extract-container hide">
						<div id="progress-extract" class="progress-bar bg-color-blue"
							role="progressbar" style="width: 0%"></div>
					</div>
					<hr class="simple">

					<p class="install-container hide">
						<span id="install-status"></span> <span id="install-percentuale"
							class="pull-right"> </span>

					</p>

					<div class="progress install-container hide">
						<div id="progress-install" class="progress-bar bg-color-blue"
							role="progressbar" style="width: 0%"></div>
					</div>


				</div>
			</div>
		</div>
        
        -->

	</div>

</div>


<?php 

//include footer
include("inc/footer.php");

?>