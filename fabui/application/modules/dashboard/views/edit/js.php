<script type="text/javascript">

var widgets;

$(document).ready(function() {
    
   	/* SET LAYOUT */
	$('.layout').on('click', function() {

        var id = $(this).attr('id');
		$('.layout').removeClass('my-selected');
		$(this).addClass('my-selected');
        set_layout(id);
		
		
	});
    
    
    
    var updateList = function(e) {
		
	};

    
    /*
    $('#nestable1').nestable({group: 1}).on('change', updateList);
    $('#nestable2').nestable({group: 1}).on('change', updateList);
    $('#nestable3').nestable({group: 1}).on('change', updateList);
    $('#nestable4').nestable({group: 1}).on('change', updateList);
    
    /**  output initial serialised data */
	$('.wid').sortable({ group: 'wid',});
    $('.wid').sortable({ group: 'wid'});
    $('.wid').sortable({ group: 'wid'});
    $('.wid').sortable({ group: 'wid'});
    
    
});



function set_layout(id){
    
    id = parseInt(id.replace('layout-', ''));
    
    switch(id){
        
        case 1:
            $("#group-1").removeClass().addClass('col-sm-12');
            $("#group-2").removeClass().addClass('col-sm-6');
            $("#group-3").removeClass().addClass('col-sm-6');
            $("#group-3").show();
            $("#group-4").hide();
            break;
        case 2:
            $("#group-1").removeClass().addClass('col-sm-12');
            $("#group-2").removeClass().addClass('col-sm-4');
            $("#group-3").removeClass().addClass('col-sm-4');
            $("#group-3").show();
            $("#group-4").removeClass().addClass('col-sm-4');
            $("#group-4").show();
            break;
        case 3:
            $("#group-1").removeClass().addClass('col-sm-12');
            $("#group-2").removeClass().addClass('col-sm-12');
            $("#group-3").hide();
            $("#group-4").hide();
            break;
        
    }
    
}







</script>