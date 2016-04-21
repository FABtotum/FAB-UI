<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa fa-user"></i> Profile </h1>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">

		<div class="well">

			<ul id="myTab1" class="nav nav-tabs bordered">
				<li class="active">
					<a href="#basic-info" data-toggle="tab"><i class="fa fa-fw fa-lg fa-pencil-square-o"></i>&nbsp;Basic info</a>
				</li>
				
				<li>
					<a href="#password-tab" data-toggle="tab"><i class="fa fa-fw fa-lg fa-key"></i>&nbsp;Password</a>
				</li>
				
				<li>
					<a href="#settings-tab" data-toggle="tab"><i class="fa fa-fw fa-lg fa-delicious"></i>&nbsp;Settings</a>
				</li>

			</ul>
			<div id="myTabContent1" class="tab-content padding-10">
				<div class="tab-pane fade in active margin-top-10" id="basic-info">

					<div class="row">

						<div class="col-sm-3">

							<div class="row">
								<div class="col-xs-12 col-md-12">
									<a href="javascript:void(0)" class="thumbnail"> <img id="img-preview" src="<?php echo $_SESSION['user']['avatar'] != '' ? $_SESSION['user']['avatar'] : base_url() . 'application/layout/assets/img/male.png'; ?>" alt=""> </a>
								</div>

							</div>

							<div class="row">

								<div class="col-xs-12">

									<a id="select-image" href="javascript:void(0);" class="btn btn-default">Select image</a>
									&nbsp;
									<a id="remove-image" href="javascript:void(0);" class="btn btn-default">Remove</a>
								</div>

								<input type="file" id="uploadFile" style="display: none">

							</div>

						</div>

						<div class="col-sm-9">

							<form id="basic-info-form" >

								<div class="form-group">

									<label>First name</label>
									<input name="first_name" id="first_name" type="text" value="<?php echo $user['first_name'] ?>" class="form-control">

								</div>

								<div class="form-group">

									<label>Last name</label>
									<input name="last_name" id="last_name" type="text" value="<?php echo $user['last_name'] ?>" class="form-control">

								</div>

								<div class="form-group">

									<label>Email</label>
									<input name="email" id="email" type="email" value="<?php echo $user['email'] ?>" class="form-control">

								</div>

								<hr>

							</form>

						</div>
					</div>

				</div>
				<div class="tab-pane fade margin-top-10" id="password-tab">
					
					<div class="row">
						<div class="col-md-6">
							<form id="password-form" >
								<div class="form-group">
									<label>Old password</label>
									<input name="old_password" id="old_password" type="password"  class="form-control">
								</div>
								
								<div class="form-group">
									<label>New password</label>
									<input name="new_password" id="new_password" type="password"  class="form-control">
								</div>
							
								<div class="form-group">
									<label>Confirm new password</label>
									<input name="confirm_new_password" id="confirm_new_password" type="password"  class="form-control">
								</div>
							
							</form>
						</div>
						
						
							
					</div>

				</div>

				<div class="tab-pane fade" id="settings-tab">

					<div class="row">

						<div class="col-sm-12">
							<div class="form-horizontal">
								<fieldset>
									<div class="form-group">
										<label class="col-md-1 control-label">Theme skin</label>
										<div class="col-md-11">
											<div class="radio">
												<label>
													<input type="radio" class="radiobox style-0" <?php echo $_SESSION['user']['theme-skin']=='smart-style-0' ? 'checked="checked"' : '' ?>
													name="theme_skin" value="smart-style-0">
													<span> Default </span> </label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" class="radiobox style-0" <?php echo $_SESSION['user']['theme-skin']=='smart-style-1' ? 'checked="checked"' : '' ?>
													name="theme_skin" value="smart-style-1">
													<span> Dark Elegance </span> </label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" class="radiobox style-0" <?php echo $_SESSION['user']['theme-skin']=='smart-style-2' ? 'checked="checked"' : '' ?>
													name="theme_skin" value="smart-style-2">
													<span> Ultra Light </span> </label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" class="radiobox style-0" <?php echo $_SESSION['user']['theme-skin']=='smart-style-3' ? 'checked="checked"' : '' ?>
													name="theme_skin" value="smart-style-3">
													<span> Google Skin </span> </label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" class="radiobox style-0" <?php echo $_SESSION['user']['theme-skin']=='smart-style-4' ? 'checked="checked"' : '' ?>
													name="theme_skin" value="smart-style-4">
													<span> PixelSmash </span> </label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" class="radiobox style-0" <?php echo $_SESSION['user']['theme-skin']=='smart-style-5' ? 'checked="checked"' : '' ?>
													name="theme_skin" value="smart-style-5">
													<span> Glass </span> </label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" class="radiobox style-0" <?php echo $_SESSION['user']['theme-skin']=='smart-style-6' ? 'checked="checked"' : '' ?>
													name="theme_skin" value="smart-style-6">
													<span> MaterialDesign </span> </label>
											</div>
										</div>
									</div>
								</fieldset>
								
								<fieldset>
									<div class="form-group">
										<label class="col-md-1 control-label">Layout</label>
										<div class="col-md-11">
											<label class="checkbox-inline">
												  <input type="checkbox" id="smart-fixed-header" class="checkbox "  <?php echo strpos($_SESSION['user']['layout'], 'fixed-header') !== false ? 'checked="checked"' : ''; ?> >
												  <span>Fixed Header</span>
											</label>
											<label class="checkbox-inline">
												  <input type="checkbox" id="smart-fixed-navigation" class="checkbox" <?php echo strpos($_SESSION['user']['layout'], 'fixed-navigation') !== false ? 'checked="checked"' : ''; ?>>
												  <span>Fixed Navigation</span>
											</label>
											<label class="checkbox-inline">
												  <input type="checkbox" id="smart-fixed-ribbon" class="checkbox" <?php echo strpos($_SESSION['user']['layout'], 'fixed-ribbon') !== false ? 'checked="checked"' : ''; ?>>
												  <span>Fixed Ribbon</span>
											</label>
											<label class="checkbox-inline">
												  <input type="checkbox" id="smart-fixed-footer" class="checkbox" <?php echo strpos($_SESSION['user']['layout'], 'fixed-page-footer') !== false ? 'checked="checked"' : ''; ?>>
												  <span>Fixed Footer</span>
											</label>
											<label class="checkbox-inline">
												  <input type="checkbox" id="smart-top-menu" class="checkbox" <?php echo strpos($_SESSION['user']['layout'], 'menu-on-top') !== false ? 'checked="checked"' : ''; ?> >
												  <span>Menu on top</span>
											</label>
										</div>
									</div>
								</fieldset>
								
								<fieldset>
									<div class="form-group">
										<label class="col-md-1 control-label">Lock screen</label>
										<div class="col-md-11">
											<?php echo form_dropdown('lock_screen', $lock_screen_options, $lock_screen, 'class="form-control" id="lock_screen"'); ?>
										</div>
									</div>
								</fieldset>
								
							</div>
						</div>
					</div>
				</div>

				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">

							<button class="btn btn-primary" id="save-button">
								<i class="fa fa-save"></i>&nbsp;Save
							</button>
						</div>
					</div>
				</div>

			</div>

		</div>
	</div>
</div>