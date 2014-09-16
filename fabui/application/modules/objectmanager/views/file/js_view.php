<script type="text/javascript">
    
    var editor;
    
    $(function () {
        
        
        <?php if(!$is_stl): ?>
        /*        
        editor = ace.edit("editor");
        editor.getSession().setMode("ace/mode/text");
        editor.renderer.setShowPrintMargin(false);
        $("#editor").show();*/
        <?php endif; ?>
        
        
        $("#submit").on('click', function() {
            
            //openWait('Saving file..');
            $('#submit').addClass("disabled");
            $('#submit').html('<i class="fa fa-spin fa-spinner"></i> Saving');
            
            <?php if(!$is_stl): ?>
            var file_content = encodeURIComponent($.trim(editor.getSession().getValue()));
            <?php endif;  ?>
            var note         = encodeURIComponent($.trim($("#note").val()));
            var name         = encodeURIComponent($.trim($("#name").val()));
            
        
            
            $.ajax({
              type: "POST",
              url: "<?php echo module_url("objectmanager").'ajax/save_file.php' ?>",
              data: { <?php if(!$is_stl): ?>file_content: file_content,<?php endif; ?> file_id: <?php echo $_file->id; ?>,  file_path : '<?php echo $_file->full_path ?>', note: note, name: name},
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
             
        
        <?php if(!$is_stl): ?>
        load_file_content();
        <?php endif; ?>
                
        $("#file_content").on('change paste keyup"', function (){
            

            
        });
          
        
    });
    
    
    
    
    function load_file_content(){
        
         $.get( "<?php echo 'http://'.$_SERVER['HTTP_HOST'].str_replace('/var/www/', '/', $_file->full_path) ?>", function( data ) {
             /*editor.setValue(data);
             $("#editor").show();*/
             
            $("#editor").html(data);
            editor = ace.edit("editor");
            editor.getSession().setMode("ace/mode/text");
            editor.renderer.setShowPrintMargin(false);
            $("#file-content-title").html('Content');
            $("#editor").show();
             
             
         });
        
        
    }
    
    
</script>