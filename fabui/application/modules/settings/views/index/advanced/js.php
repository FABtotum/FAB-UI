<script type="text/javascript">

    var editor;

    $(function () {
        
        editor = ace.edit("editor");
        editor.getSession().setMode("ace/mode/python");
        
        $("#editor").show();
        
        $("#submit").on('click', function() {
            save_file();
            return false;
            
        });  
        
    });
    
    
    
    function save_file(){
        
       
        var file_content = encodeURIComponent($.trim(editor.getSession().getValue()));
        
        $('#submit').addClass("disabled");
        $('#submit').html('<i class="fa fa-spin fa-spinner"></i> Saving');
        
        
        $.ajax({
              type: "POST",
              url: "<?php echo module_url("settings").'ajax/save_file.php' ?>",
              data: { file_content: file_content, file_path : '<?php echo $_boot_script_file ?>'},
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
        
    }
    
    
    
    
</script>