<script type="text/javascript">


    var files_selected = new Array();
    var new_file;
    var input_file = <?php echo $file_id ?>;

    $(function () {
        
        
        
        /** CHECK INPUT FILE */
        $("input[name=checkbox-file]").each( function( index, element ){
            if($(this).attr('data-file-id') == input_file){
                $(this).prop('checked', true);
            }
                                 
        });
        
        
        
        $("#merge-button").click(function(){
            
            
            files_selected.length = 0;
            
            $("input[name=checkbox-file]").each( function( index, element ){
                    if($( this ).is(':checked') == true ){
                        files_selected.push($(this).attr('data-file-id'));
                    }                    
            });

            if(files_selected.length < 2){
            
                $.smallBox({
					title : "Please select at least 2 files",
					color : "#C46A69",
					iconSmall : "fa fa-warning bounce animated",
					timeout : 4000
				});
                
                return false;
                
            }
            
 
            $.SmartMessageBox({
    				title: "This operation would take few minutes",
    				content: "Continue?",
    				buttons: '[No][Yes]'
 			}, function(ButtonPressed) {
    			
                if (ButtonPressed === "Yes") {
                    
                    merge();
    				
    			}
    			if (ButtonPressed === "No") {
    
    			}
    
 			});

        });
        
    });
    
    
    
    
    function merge(){
        
        
        openWait('Merging files..');
        
        
        $.ajax({
              type: "POST",
              url: "<?php echo module_url("objectmanager").'ajax/merge.php' ?>",
              data: {obj_id: <?php echo $obj_id; ?> ,files_selected: files_selected, output: $("#output_name").val()},
              dataType: 'json'
            }).done(function( response ) {
                
                $('.files').slideUp('fast', function(){
                    $('.response').slideDown('fast', function(){
                        new_file = response.file_id;   
                    });
                    
                });
                closeWait();
                
              
            });
            
              
    }
    
    
    
    function go_to_file(){
        
        if(new_file != ''){
             document.location.href = '<?php echo site_url('objectmanager/manage/'.$obj_id); ?>/' + new_file;
        }

    }
</script>