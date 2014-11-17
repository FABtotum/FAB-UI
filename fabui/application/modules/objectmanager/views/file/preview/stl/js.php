<script type="text/javascript">


	var thingiurlbase = '';
	var thingiview;
	
	$(function() {
		
		
		
		init();
		$('#object-color').colorpicker();
		
		
		$(".material").on('click', setMaterial);
		$(".rotation").on('click', setRotation);
		$(".plane").on('click', setPlane);
      	
      
   });
   
   
   
   function init(){
   		
   		
   		thingiurlbase = "/fabui/application/layout/assets/js/plugin/thingiview/";
      	thingiview = new Thingiview("viewer");
      	thingiview.setObjectColor('#ffffff'); 
      	thingiview.setBackgroundColor('#000000');
      	thingiview.initScene();
      	
      	thingiview.loadSTL('<?php echo str_replace('/var/www', '', $file->full_path); ?>');
      	thingiview.setRotation(false);
      	
      	closeWait();
   	
   }
   
    
    function getUrlVars() {
    	
    	var vars = {};
    	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
    		vars[key] = value;
    	});
    	
    	return vars;
    }
    
    
    function setMaterial(){
    	
    	thingiview.setObjectMaterial($(this).attr("data-action"));
    	
    	
    	
    	$(".material").removeClass('active btn-primary').addClass('btn-default');
    	$(this).addClass('active').addClass('btn-primary');
    	
    	
    }
    
    
  	function setRotation(){
  		
  		var rotate = $(this).attr('data-action') == 'on';
  		thingiview.setRotation(rotate);
  		
  		
    	
    	$(".rotation").removeClass('active btn-primary').addClass('btn-default');
    	$(this).addClass('active').addClass('btn-primary');
  	}
  	
  	
  	function setPlane(){
  		
  		var plane = $(this).attr('data-action') == 'show';
  		thingiview.setShowPlane(plane);
  		
  		$(".plane").removeClass('active btn-primary').addClass('btn-default');
    	$(this).addClass('active').addClass('btn-primary');
  	}
  	
       
  </script>