<script type="text/javascript">


    var editor1;
    var editor2;
    var editor0;

    $(function () {
        
          editor1 = ace.edit("editor1");
          editor1.getSession().setMode("ace/mode/text");
          
          editor2 = ace.edit("editor2");
          editor2.getSession().setMode("ace/mode/text");
          
          
          editor0 = ace.edit("editor0");
          editor0.getSession().setMode("ace/mode/text");
          
          $("#editor1").show();
          $("#editor2").show();
          
          $("#submit").on('click', function() {
            
            $("#start_gcode").val(editor1.getSession().getValue());
            $("#end_gcode").val(editor2.getSession().getValue());
            
            
            $("#create-form").submit();
            
        });
        
        
        
        $(".presets").on('change', function(){
        
            if($(this).val() != ''){
                $.get( "<?php echo 'http://'.$_SERVER['HTTP_HOST'] ?>" + $(this).val()  , function( data ) {
                  editor0.getSession().setValue(data);
                  
                  
                });
            }else{
                editor0.setValue('');
            }
            
        });    
        
    });
</script>