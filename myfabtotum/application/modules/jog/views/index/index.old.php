<section>
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget well" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false"
			data-widget-custombutton="false" data-widget-sortable="false">
				<div>
					<div class="widget-body">
						<ul id="myTab3" class="nav nav-tabs tabs-pull-right bordered">
							<li>
								<a href="#config" data-toggle="tab"><i class="fa fa-wrench"></i>
								Setup </a>
							</li>
							<li class="active pull-left">
								<a href="#jog" data-toggle="tab"><i
								class="fa fa-lg fa-gamepad"></i> JOG </a>
							</li>
						</ul>
						<div id="myTabContent3" class="tab-content padding-10">
							<div class="tab-pane fade in active" id="jog">
								<div class="row" style="margin-top: 20px;">
									<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 directions-container">
										<div class="well text-center">
											<table style="width: 100%;">
												<tr>
													<td>
														<table class="table table-jog table-no-border">
															<tbody>
																<tr>
																	<td>
																		<button data-attribue-direction="up-left" data-attribute-keyboard="103" class="btn btn-default btn-lg directions btn-circle btn-xl">
																			<i class="fa fa-arrow-left fa-1x fa-rotate-45">
																			</i>
																		</button>
																		<button data-attribue-direction="up" data-attribute-keyboard="104" class="btn btn-default btn-lg directions btn-circle btn-xl">
																			<i class="fa fa-arrow-up fa-1x">
																			</i>
																		</button>
																		<button data-attribue-direction="up-right" data-attribute-keyboard="105" class="btn btn-default btn-lg directions btn-circle btn-xl">
																			<i class="fa fa-arrow-up fa-1x fa-rotate-45">
																			</i>
																		</button>
																	</td>
																</tr>
																<tr>
																	<td>
																		<button data-attribue-direction="left" data-attribute-keyboard="100" class="btn btn-default btn-lg directions btn-circle btn-xl">
																			<span class="glyphicon glyphicon-arrow-left ">
																			</span>
																		</button>
																		<button data-attribue-direction="home" data-attribute-keyboard="101" class="btn btn-default btn-lg btn-circle btn-xl directions ">
																			<i class="fa fa-home">
																			</i>
																		</button>
																		<button data-attribue-direction="right" data-attribute-keyboard="102" class="btn btn-default btn-lg directions btn-circle btn-xl">
																			<span class="glyphicon glyphicon-arrow-right">
																			</span>
																		</button>
																	</td>
																</tr>
																<tr>
																	<td>
																		<button data-attribue-direction="down-left" data-attribute-keyboard="97" class="btn btn-default btn-lg directions btn-circle btn-xl">
																			<i class="fa fa-arrow-down fa-rotate-45 ">
																			</i>
																		</button>
																		<button data-attribue-direction="down" data-attribute-keyboard="98" class="btn btn-default btn-lg directions btn-circle btn-xl">
																			<i class="glyphicon glyphicon-arrow-down ">
																			</i>
																		</button>
																		<button data-attribue-direction="down-right" data-attribute-keyboard="99" class="btn btn-default btn-lg directions btn-circle btn-xl">
																			<i class="fa fa-arrow-right fa-rotate-45">
																			</i>
																		</button>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td>
														<table>
                                                           
															<tr>
																<td>
																	<div class="btn-group-vertical">
																		<button type="button" class="btn btn-default axisz" data-attribute-step="10" data-attribute-function="zdown">
																			<i class="fa fa-angle-double-up"></i> 10
																		</button>
																		<button type="button" class="btn btn-default axisz" data-attribute-step="5" data-attribute-function="zdown">
																			<i class="fa fa-angle-double-up"></i> 5
																		</button>
																		<button type="button" class="btn btn-default axisz" data-attribute-step="1" data-attribute-function="zdown">
																			<i class="fa fa-angle-double-up"></i> 1
																		</button><hr>
                                                                        <button type="button" class="btn btn-default axisz" data-attribute-step="1" data-attribute-function="zup">
																			<i class="fa fa-angle-double-down"></i> 1
																		</button>
																		<button type="button" class="btn btn-default axisz" data-attribute-step="5" data-attribute-function="zup">
																			<i class="fa fa-angle-double-down"></i> 5
																		</button>
																		<button type="button" class="btn btn-default axisz" data-attribute-step="10" data-attribute-function="zup">
																			<i class="fa fa-angle-double-down"></i> 10
																		</button>
																	</div>
																</td>
															</tr>
                                                            
														</table>
													</td>
												</tr>
											</table>
                                            <hr>
											<button id="zero-all" type="button" class="btn btn-default btn-lg btn-block">
												<i class="fa fa-bullseye">
												</i>
												Zero All
											</button>
										</div>
									</div>
									<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
										<div class=" well text-center">
											<div class="knobs-demo">
												<input class="knob" data-width="200" data-cursor=true data-step="0.5" data-min="1" data-max="360" data-thickness=.3 data-fgColor="#A0CFEC" data-displayInput=true>
											</div>
										</div>
									</div>
								</div>
								<div class="row"  style="margin-top: 20px;">
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="well">
										<div class="smart-form ">
											<fieldset>
												<section>
													<label class="label">
														Motors
													</label>
													<div class="inline-group">
														<label class="radio">
															<input type="radio" name="motors" <?php echo $_motors=="on" ? "checked='checked'" : ""; ?>
															checked="checked" value="on">
															<i>
															</i>
															On
														</label>
														<label class="radio">
															<input type="radio" <?php echo $_motors=="off" ? "checked='checked'" : ""; ?>
															name="motors" value="off">
															<i>
															</i>
															Off
														</label>
													</div>
												</section>
												<section>
													<label class="label">
														Coordinates
													</label>
													<div class="inline-group">
														<label class="radio">
															<input type="radio" name="coordinates" <?php echo $_coordinates=="relative" ? "checked='checked'" : ""; ?>
															value="relative">
															<i>
															</i>
															Relative
														</label>
														<label class="radio">
															<input type="radio" <?php echo $_coordinates=="absolute" ? "checked='checked'" : ""; ?>
															value="absolute" name="coordinates">
															<i>
															</i>
															Absolute
														</label>
													</div>
												</section>
												<section>
												</section>
											</fieldset>
										</div>
										<div class="panel-group smart-accordion-default" id="accordion">
											<div class="panel panel-default">
												<div class="panel-heading">
													<h4 class="panel-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="collapsed"> <i
														class="fa fa-lg fa-angle-down pull-right"></i> <i
														class="fa fa-lg fa-angle-up pull-right"></i>MDI
														</a>
													</h4>
												</div>
												<div id="collapseOne" class="panel-collapse collapse">
													<div class="panel-body no-padding">
														<div class="smart-form">
															<fieldset>
																<section>
																	<label class="textarea">
																		<i class="icon-prepend fa fa-terminal">
																		</i>
																		<textarea rows="3" id="mdi-txt" placeholder="G code">
																		</textarea>
																		<b class="tooltip tooltip-top-left">
																			<i class="fa fa-warning txt-color-teal">
																			</i>
																			Insert your G code
																		</b>
																	</label>
																</section>
																<section>
																	<button id="exece-mdi" class="btn btn-default btn-lg">
																		<i class="fa fa-gear">
																		</i>
																		Execute
																	</button>
																</section>
															</fieldset>
														</div>
													</div>
												</div>
											</div>
										</div>
                                        </div>
									</div>
									<div class="col-sx-12 col-sm-6">
										<h5>
											<small>
												console
											</small>
										</h5>
										<div class="console" style="overflow: auto;">
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="config">
								<div class="row">
									<div class="col-sx-12 col-sm-6">
										<form action="demo-contacts.php" method="post" id="contact-form" class="smart-form">
											<header>
												Contacts form
											</header>
											<fieldset>
												<section>
													<label class="label">
														Unit
													</label>
													<label class="select">
														<select name="unit" id="unit">
															<?php $selected=$_unit=='inch' ? 'selected' : ''; ?>
																<option <?php echo $selected ?>
																	value="inch">inches
																</option>
																<?php $selected=$_unit=='mm' ? 'selected' : ''; ?>
																	<option <?php echo $selected ?>
																		value="mm">millimiters
																	</option>
														</select>
														<i>
														</i>
													</label>
												</section>
												<section>
													<label class="label">
														Step
													</label>
													<label class="input">
														<input type="text" value="<?php echo $_step ?>" name="step" id="step">
													</label>
												</section>
												<section>
													<label class="label">
														Feedrate
													</label>
													<label class="input">
														<input type="text" value="<?php echo $_feedrate ?>" name="feedrate" id="feedrate">
													</label>
												</section>
												<hr class="simple">
												<section>
													<button id="save-conf" type="button" class="btn btn-default btn-lg btn-block">
														<i class="fa fa-save">
														</i>
														Save
													</button>
												</section>
											</fieldset>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- end widget content -->
				</div>
				<!-- end widget div -->
			</div>
		</article>
	</div>
</section>