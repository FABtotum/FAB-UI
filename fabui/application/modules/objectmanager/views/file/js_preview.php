<script type="text/javascript">


function error(msg) {
  alert(msg);
}

function loadFile(path, callback /* function(contents) */) {
  $.get(path, null, callback, 'text').error(function() { error() });
}




var scene = null;
var object = null;

function openGCodeFromPath(path) {
	

  loadFile(path, function(gcode) {
  	
    object = createObjectFromGCode(gcode);
    closeWait();  
    scene.add(object);
    
  });
}

function openGCodeFromText(gcode) {
  
  if (object) {
    scene.remove(object);
  }
  object = createObjectFromGCode(gcode);
  scene.add(object);

}


$(function() {

	if (!Modernizr.webgl) {
		
	    $("#no-webgl").show();
	    $("#<?php echo $widget_id; ?>").hide();
	    return;
	}
	
	if (!Modernizr.localstorage) {
	    alert("Man, your browser is ancient. I can't work with this. Please upgrade.");
		return;
	}
	
	
	$('#renderArea').css('height', ($("#main").height() + 50) + 'px' );
	
	var uri_file = '<?php echo str_replace('/var/www', '', $file->full_path) ?>';

	
	scene = createScene($('#renderArea'));
	 
	openWait('Loading GCode file');
	openGCodeFromPath(uri_file);
  
 
});


	
</script>