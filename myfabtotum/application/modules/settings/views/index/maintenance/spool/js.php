<script type="text/javascript">


    var choice = '';

    $(function () {
        
        
        
        $(".choice-button").on('click', function (){
                
            choice = $(this).attr('data-action');
            
            $( ".choice" ).slideUp( "slow", function() {});
            $("." + choice + "-choice").slideDown('slow');
            $(".re-choice").slideDown('slow');
            $(".start").slideDown('slow');
                
        });
        
        
        $(".re-choice-button").on('click', function(){
            
            
            $("." + choice + "-choice").slideUp('slow');
            $( ".choice" ).slideDown( "slow", function() {});
            $(".re-choice").slideUp('slow');
            $(".start").slideUp('slow');
            
        });
        
        
        $(".start-button").on('click', do_macro);
        
        
    });
    
    
    
    
    function do_macro(){
        
        $.ajax({
              type: "POST",
              url: "<?php echo module_url("settings").'ajax/spool.php' ?>",
              data: { action: choice},
              dataType: 'json'
        }).done(function( response ) {
            
            
        });
        
        
        
    }

</script>