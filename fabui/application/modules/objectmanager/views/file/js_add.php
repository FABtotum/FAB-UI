<script type="text/javascript">

$(document).ready(function() {

    init_tree();

    /*
	Dropzone.autoDiscover       = false;
	Dropzone.options.mydropzone = false;
    */

	var files = new Array(); /*lista con gli id dei file uploadati da mandare in post nella form submit*/

 	Dropzone.options.mydropzone = {

		url: "<?php echo site_url('objectmanager/upload'); ?>",
		dictResponseError: 'Error uploading file!',
		acceptedFiles : '<?php echo $accepted_files ?>',
		autoProcessQueue: false,
		parallelUploads: 1,

		init: function (){

			 var submitButton = document.querySelector("#save-object");
		     var myDropzone = this;

			 submitButton.addEventListener("click", function() {


                openWait("Uploading e saving files..."); 
				  
			      	if(myDropzone.getQueuedFiles().length > 0){
				      	myDropzone.processQueue(); 
			      	}
				    else{
                        add_usb_files(); 
				    	$("#file-form").submit();
				    }

			
		});


		myDropzone.on("complete", function (file) {

		  files.push(file.xhr.response); /*aggiungo l'id del file uploadato all'elenco dei files da mandare in post*/
		
		  	if (myDropzone.getUploadingFiles().length === 0 && myDropzone.getQueuedFiles().length === 0) {
		  	   
					$('#files').val(files.toString());
                    add_usb_files();
					$("#file-form").submit();
					
		  }else{
			  $( "#save-object" ).trigger( "click" );
		  }
					
				     
		});

		 
			  
		}
	};
    
    
    $("#check-usb").on('click', function() {
        check_usb();
    });


	
});


function init_tree(){
    
    $('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
    
    $('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch').on('click', function(e) {
        
        var children = $(this).parent('li.parent_li').find(' > ul > li');        
        
        load_tree($(this));
        
		if (children.is(':visible')) {
			children.hide('fast');
            $(this).attr("data-loaded","false");
			$(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-lg fa-plus-circle');
		} else {
			children.show('fast');
			$(this).attr('title', 'Collapse this branch').find(' > i').removeClass().addClass('fa fa-lg fa-minus-circle');
		}
		e.stopPropagation();         
    }); 
}

function load_tree(obj){
    
    var folder = obj.attr("data-folder");
    var loaded = obj.attr("data-loaded") == "true" ? true : false;
    
    
    if(!loaded){
        obj.next('ul').html('');
        
    	$.ajax({
    	   type: "POST",
    	   url: "<?php echo module_url('objectmanager/ajax/tree.php') ?>/",
           data: {folder: folder},
    	   dataType: 'json'
    	}).done(function(response) {
            var tree = response.tree;
            if(tree.length > 0){
                
                $.each(tree, function(i, item) {
                
                    var element = '';
                    
                    if(item.charAt((item.length - 1)) == '/'){
                        element = folder_item(item, folder);
                    }else{
                        element = file_item(item, folder);
                    }
                    obj.next('ul').append(element);
                   
                });
                
                
                obj.attr("data-loaded","true");
                
                 
            
            
                $(".subfolder").on('click', function () {
                    
                    
                    load_tree($(this));
                    
                    
                    var children = $(this).parent('li.parent_li').find(' > ul > li');
                    
                    if (children.is(':visible')) {
            			children.hide('fast');
                        
            			$(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-lg fa-plus-circle');
            		} else {
            			children.show('fast');
            			$(this).attr('title', 'Collapse this branch').find(' > i').removeClass().addClass('fa fa-lg fa-minus-circle');
            		}
                });
                
                
            }else{
                obj.find('i').removeClass();
            }

    	});
    }
}

function file_item(item, parent){
    
    
    var item_label = item.replace(parent, '');
    
    var html = '';
    
    html += '<li style="list-item;"><span>';
    html += '<label class="checkbox inline-block usb-file">';
    
    html += '<input type="checkbox" name="checkbox-inline" value="'+ item +'" />';
    html += '<i></i> '+ item_label;
    
    html += '</label>';
    html += '</span></li>';
    
    return html;
    
}


function folder_item(item, parent){
    
    var html = '';
    
    html += '<li class="parent_li" role="treeitem">';
    
    html += '<span class="subfolder" data-loaded="false" data-folder="' + item +'">';
    
    item = item.replace(parent, '');
    item = item.slice(0,-1);
    
    html += '<i class="fa fa-lg fa-plus-circle"></i> ' + item;
    html += '</span>';
    
    html += '<ul></ul>';
    
    html += '</li>';
    
    return html;
    
}


function add_usb_files(){
    
     if($('.tree').length > 0){
                            
        var usb_files = new Array();                        
        $( ".tree" ).find("input").each(function( index ) {
            
            
            var input = $(this);
            
            if(input.is(':checked')){
            
                usb_files.push(input.val());
                
            }
            
        });
        $('#usb_files').val(usb_files.toString());
        
    }
    
}

function check_usb(){
    
    $("#check-usb").html("Checking...");
    
    $.ajax({
    	   type: "POST",
    	   url: "<?php echo module_url('objectmanager/ajax/check_usb.php') ?>/",
    	   dataType: 'html'
   	}).done(function(response) {
        
        if(response != ""){
            $("#usb").html(response);
            init_tree();
        }else{
            $("#check-usb").html("Reload");
        }    
   	    
    });
}



</script>
