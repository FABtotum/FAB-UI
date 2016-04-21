<div class="step-pane <?php echo  $_task && $_task_attributes['step'] == 1 ? 'active': '' ?> <?php echo  !$_task  ? 'active': '' ?>"  id="step1">
	
    
    <div class="row">
    
        <div class="col-sm-12">
        
            <div class="form-horizontal">
                <fieldset>							
    				<div class="form-group">
    					
    					<div class="col-md-2 margin-bottom-10">
    						<div class="radio">
    							<label>
    								<input type="radio" class="radiobox" checked="checked" name="radio-object" value="new" />
    								<span>Create new object</span> 
    							</label>
    						</div>
    					</div>
                        
                        <div class="col-md-10">
                            <input id="name-object" name="name-object" type="text" class="form-control animated  fadeIn fast" placeholder="Object name" />
                        </div>
                        
                        
    				</div>
                    
                    <div class="form-group">
    					<div class="col-md-2 margin-bottom-10">
    						<div class="radio">
    							<label>
    								<input type="radio" class="radiobox " name="radio-object" value="existing" />
    								<span>Add scan to an existing object</span> 
    							</label>
    						</div>
    					</div>
                        <div class="col-md-10">
                            
                            
                        
                            <select class="form-control object-select animated  fadeIn fast" style="display: none;">
                                <option value="">-- Select object -- </option>
                                <?php foreach($_objects as $_obj): ?>
                                
                                <option value="<?php echo $_obj->id ?>"><?php echo $_obj->obj_name.' - '.$_obj->obj_description ?></option>
                                
                                <?php endforeach; ?>
                                
                                							
							</select>
                        
                        </div>
    				</div>
    			</fieldset>
            </div>
        </div>
    
    </div>
    
    
    <!--
    <div class="row">
		<div class="col-sm-6">
			<h2 class="text-primary">Select scan mode</h2>
		</div>

	</div>
	-->

	<div class="row">
	
	<?php foreach($mode_list as $mode): ?>
	
		<?php $configuration = json_decode($mode->values) ?>
		
		<?php //if($mode->name != 'sweep'): ?>
		<div class="col-sm-3">
		
			<div class="scan-mode  well well-sm text-center " data-id="<?php echo $mode->id; ?>" data-type="<?php echo $mode->name ?>" data-title="<?php echo $configuration->info->name ?>">
			
				<h6><?php echo $configuration->info->name ?></h6>
				<div class="row">
					<div class="text-align-center mode-image">
						<img class="img-responsive" style="display: inline; max-width: 50%;" src="<?php echo base_url() .'/application/modules/scan/assets/img/'.strtolower($mode->name).'.png' ?>">
					</div>
                    <div class="mode-description" style="display:none;">
					   <p><?php echo $configuration->info->description ?></p>
                    </div>
				</div>
			
			</div>
		</div>
		<?php //endif; ?>
	
	<?php endforeach; ?>

	</div>

</div> 
