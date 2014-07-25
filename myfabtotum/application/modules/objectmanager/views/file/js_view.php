<script type="text/javascript">
    
    var editor;
    
    $(function () {
                
        editor = ace.edit("editor");
        editor.getSession().setMode("ace/mode/text");
        editor.renderer.setShowPrintMargin(false);
        
        
        $("#editor").show();
        
        
        $("#submit").on('click', function() {
            
            //openWait('Saving file..');
            $('#submit').addClass("disabled");
            $('#submit').html('<i class="fa fa-spin fa-spinner"></i> Saving');
            
            var file_content = encodeURIComponent($.trim(editor.getSession().getValue()));
             var note         = encodeURIComponent($.trim($("#note").val())); 
            
            $.ajax({
              type: "POST",
              url: "<?php echo module_url("objectmanager").'ajax/save_file.php' ?>",
              data: { file_content: file_content, file_id: <?php echo $_file->id; ?>,  file_path : '<?php echo $_file->full_path ?>', note: note},
              dataType: 'json'
            }).done(function( response ) {
                
                $.smallBox({
    				title : "Success",
    				content : "<i class='fa fa-check'></i> The file was saved",
    				color : "#659265",
    				iconSmall : "fa fa-thumbs-up bounce animated",
                    timeout : 4000
                });
                
                $('#submit').removeClass("disabled");
                $('#submit').html('<i class="fa fa-save"></i> Save');
              
            });
            
            
            
            return false;
            //$("#view-form").submit();
            
        });
             
        
        /*load_file_content();*/
                
        $("#file_content").on('change paste keyup"', function (){
            
            
            console.log("Changed..");
            console.log($(this).val());
            
        });
          
        
    });
    
    
    
    
    function load_file_content(){
        
         $.get( "<?php echo 'http://'.$_SERVER['HTTP_HOST'].str_replace('/var/www/', '/', $_file->full_path) ?>", function( data ) {
             editor.setValue(data);
             $("#editor").show();
         });
        
        
    }
    
    
</script>