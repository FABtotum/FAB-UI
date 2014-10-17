<script type="text/javascript">
   


	$(function() {
		
		
		openWait('Loading STL file');
		console.log('init');
		
      	thingiurlbase = "/fabui/application/layout/assets/js/plugin/thingiview/";
      	thingiview = new Thingiview("viewer");
      	thingiview.setObjectColor('#C0D8F0');
      	thingiview.initScene();
      
      	thingiview.loadSTL('<?php echo str_replace('/var/www', '', $file->full_path); ?>');
      	thingiview.setRotation(false);
      	
      	closeWait();
      
   });
   
    
    function getUrlVars() {
    	var vars = {};
    	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
    		vars[key] = value;
    	});
    	return vars;
    }    
  </script>