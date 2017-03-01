<style>.hardware-save{margin-right:10px;}</style>
<div id="myTabContent1" class="tab-content padding-10">
	<!-- hardware -->
	<div class="tab-pane fade in active margin-top-10 padding-10" id="basic-info">
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
				</form>
			</div>
		</div>
	</div>
	<!-- password  -->
	<div class="tab-pane fade margin-top-10 padding-10" id="password-tab">			
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
	<!-- settings -->
	<div class="tab-pane fade" id="settings-tab">
		
		
		<div class="row">
			<div class="col-sm-12">
				<div class="smart-form">
					<fieldset>
						<section>
							<label class="label">Theme Skin</label>
							<div class="row">
								<div class="col col-4">
									<label class="radio">
										<input type="radio" name="theme_skin" value="smart-style-0" <?php echo $_SESSION['user']['theme-skin']=='smart-style-0' ? 'checked="checked"' : '' ?> >
										<i></i>Default</label>
									<label class="radio">
										<input type="radio" name="theme_skin" value="smart-style-1" <?php echo $_SESSION['user']['theme-skin']=='smart-style-1' ? 'checked="checked"' : '' ?> >
										<i></i>Dark Elegance</label>
									<label class="radio">
										<input type="radio" name="theme_skin" value="smart-style-2" <?php echo $_SESSION['user']['theme-skin']=='smart-style-2' ? 'checked="checked"' : '' ?> >
										<i></i>Ultra Light</label>
									<label class="radio">
										<input type="radio" name="theme_skin" value="smart-style-3" <?php echo $_SESSION['user']['theme-skin']=='smart-style-3' ? 'checked="checked"' : '' ?> >
										<i></i>Google Skin</label>
								</div>
								<div class="col col-4">
									<label class="radio">
										<input type="radio" name="theme_skin" value="smart-style-4" <?php echo $_SESSION['user']['theme-skin']=='smart-style-4' ? 'checked="checked"' : '' ?> >
										<i></i>PixelSmash</label>
									<label class="radio">
										<input type="radio" name="theme_skin" value="smart-style-5" <?php echo $_SESSION['user']['theme-skin']=='smart-style-5' ? 'checked="checked"' : '' ?> >
										<i></i>Glass</label>
									<label class="radio">
										<input type="radio" name="theme_skin" value="smart-style-6" <?php echo $_SESSION['user']['theme-skin']=='smart-style-6' ? 'checked="checked"' : '' ?> >
										<i></i>MaterialDesign</label>
								</div>
							</div>
						</section>
					</fieldset>
					<fieldset>
						<section>
							<label class="label">Layout options</label>
							<div class="inline-group">
								<label class="checkbox">
									<input type="checkbox" id="smart-fixed-header"  <?php echo strpos($_SESSION['user']['layout'], 'fixed-header') !== false ? 'checked="checked"' : ''; ?> >
									<i></i>Fixed Header
								</label>
								<label class="checkbox">
									<input type="checkbox" id="smart-fixed-navigation" checked="checked" <?php echo strpos($_SESSION['user']['layout'], 'fixed-navigation') !== false ? 'checked="checked"' : ''; ?>>
									<i></i>Fixed Navigation
								</label>
								<label class="checkbox">
									<input type="checkbox" id="smart-fixed-ribbon" <?php echo strpos($_SESSION['user']['layout'], 'fixed-ribbon') !== false ? 'checked="checked"' : ''; ?>>
									<i></i>Fixed Ribbon
								</label>
								<label class="checkbox">
									<input type="checkbox" id="smart-fixed-footer" <?php echo strpos($_SESSION['user']['layout'], 'fixed-page-footer') !== false ? 'checked="checked"' : ''; ?>>
									<i></i>Fixed Footer
								</label>
								<label class="checkbox">
									<input type="checkbox" id="smart-top-menu" <?php echo strpos($_SESSION['user']['layout'], 'menu-on-top') !== false ? 'checked="checked"' : ''; ?>>
									<i></i>Menu on top
								</label>
							</div>
						</section>
					</fieldset>
					<fieldset>
						<section>
							<label class="label">Lock screen</label>
							<label class="select">
								<?php echo form_dropdown('lock_screen', $lock_screen_options, $lock_screen, 'class="form-control" id="lock_screen"'); ?> <i></i>
							</label>
						</section>
					</fieldset>
					<fieldset>
						<div class="row">
							<section class="col col-3">
								<label class="label">Reports</label>
								<label class="toggle">
									<input type="checkbox"  id="end_print_email" <?php echo $_SESSION['user']['end-print-email'] ? 'checked="checked"' : ''; ?> >
									<i data-swchon-text="ON" data-swchoff-text="OFF"></i>Email notification on end print</label>
							</section>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="widget-footer text-right">
	<button class="btn btn-primary" id="save-button"><i class="fa fa-save"></i>&nbsp;Save</button>
</div>

