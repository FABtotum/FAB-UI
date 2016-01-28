<script type="text/javascript">
    
    var editor;
    var contentLoaded = false;
    
    $(function () {
        
        
             
        $("#save").on('click', function() {
               
            $('#save').addClass("disabled");
            $('#save').html('<i class="fa fa-spin fa-spinner"></i> Saving');
            
            var note         = encodeURIComponent($.trim($("#note").val()));
            var name         = encodeURIComponent($.trim($("#name").val()));
            var file_content = '';
            
            
            var data = { file_id: <?php echo $_file->id; ?>,  file_path : '<?php echo $_file->full_path ?>', note: note, name: name};
            
            
           	if($('#also-content').is(":checked")){
           		data.file_content = encodeURIComponent($.trim(editor.getSession().getValue()));
           	}
           	
                
            $.ajax({
              type: "POST",
              url: "<?php echo module_url("objectmanager").'ajax/save_file.php' ?>",
              data: data,
              dataType: 'json'
            }).done(function( response ) {
                
                $.smallBox({
    				title : "Success",
    				content : "<i class='fa fa-check'></i> The file was saved",
    				color : "#659265",
    				iconSmall : "fa fa-thumbs-up bounce animated",
                    timeout : 4000
                });
                
                $('#save').removeClass("disabled");
                $('#save').html('<i class="fa fa-save"></i> Save');
              
            });
            
            
            
            return false;
            
        });
             
        
        <?php if(!$is_stl): ?>
         $("#load-content").on('click', load_file_content);
        <?php endif; ?>
        
        
       
                
        $("#file_content").on('change paste keyup"', function (){
            

            
        });
          
        
    });
    
    
    
    
    function load_file_content(){
    	
    	if(!contentLoaded){
    		
    		$(".btn").addClass('disabled');
    		
	   		$.get( "<?php echo 'http://'.$_SERVER['HTTP_HOST'].str_replace('/var/www/', '/', $_file->full_path)."?t=".time() ?>", function( data ) {
	        	$("#editor").html(data);
	            editor = ace.edit("editor");
	            editor.getSession().setMode("ace/mode/gcode");
	            editor.renderer.setShowPrintMargin(false);
	            $("#file-content-title").html('Content');
	            $("#editor").show();
	            $(".btn").removeClass('disabled');
	            $("#load-content").addClass('disabled');
	            $("#also-content").removeAttr('disabled');
	            contentLoaded = true;
	         });
        }
        
        
    }
    
    
</script>