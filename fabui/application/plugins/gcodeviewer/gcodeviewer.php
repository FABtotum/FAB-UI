<?php
/*
Plugin Name: Gcodeviewer
Plugin URI: http://www.thingiverse.com/thing:35248
Version: 1.0
Description: gCodeViewer is a visual GCode visualizer, viewer and analyzer in your own browser! It works on any OS in almost any modern browser (chrome, ff, safari 6, opera, ie10 should work too). All you need to do - is drag your *.gcode file to the designated zone.
Author: Joshua Parker
Author URI: http://www.thingiverse.com/hudbrog/designs
Plugin Slug: gcodeviewer
*/
 
class Gcodeviewer extends Plugin {

public function __construct()
	{
		parent::__construct();
		
		//$this->layout->add_css_file(array('src'=>'application/modules/objectmanager/assets/css/filemanager.css', 'comment'=>'css for filemanager module'));
		
		$this->layout->add_css_file(array('src'=>'application/plugins/gcodeviewer/assets/css/cupertino/jquery-ui-1.9.0.custom.css', 'comment'=>'GCODEVIEWER'));
		$this->layout->add_css_file(array('src'=>'application/plugins/gcodeviewer/assets/lib/codemirror.css', 'comment'=>'GCODEVIEWER'));
		$this->layout->add_css_file(array('src'=>'application/plugins/gcodeviewer/assets/css/style.css', 'comment'=>'GCODEVIEWER'));
		
		
		$this->layout->add_js_file(array('src'=>'application/plugins/gcodeviewer/assets/lib/codemirror.js', 'comment'=>'GCODEVIEWER'));
		$this->layout->add_js_file(array('src'=>'application/plugins/gcodeviewer/assets/lib/mode_gcode/gcode_mode.js', 'comment'=>'GCODEVIEWER'));
		$this->layout->add_js_file(array('src'=>'application/plugins/gcodeviewer/assets/lib/three.js', 'comment'=>'GCODEVIEWER'));
		$this->layout->add_js_file(array('src'=>'application/plugins/gcodeviewer/assets/lib/modernizr.custom.09684.js', 'comment'=>'GCODEVIEWER'));
		$this->layout->add_js_file(array('src'=>'application/plugins/gcodeviewer/assets/lib/TrackballControls.js', 'comment'=>'GCODEVIEWER'));
		$this->layout->add_js_file(array('src'=>'application/plugins/gcodeviewer/assets/lib/zlib.min.js', 'comment'=>'GCODEVIEWER'));
		$this->layout->add_js_file(array('src'=>'application/plugins/gcodeviewer/assets/js/ui.js', 'comment'=>'GCODEVIEWER'));
		$this->layout->add_js_file(array('src'=>'application/plugins/gcodeviewer/assets/js/gCodeReader.js', 'comment'=>'GCODEVIEWER'));
		$this->layout->add_js_file(array('src'=>'application/plugins/gcodeviewer/assets/js/renderer.js', 'comment'=>'GCODEVIEWER'));
		$this->layout->add_js_file(array('src'=>'application/plugins/gcodeviewer/assets/js/analyzer.js', 'comment'=>'GCODEVIEWER'));
		$this->layout->add_js_file(array('src'=>'application/plugins/gcodeviewer/assets/js/renderer3d.js', 'comment'=>'GCODEVIEWER'));
		$this->layout->add_js_file(array('src'=>'application/plugins/gcodeviewer/assets/js/Worker.js', 'comment'=>'GCODEVIEWER'));
		
		
	
		
		
		

	}

	public function index(){

		$this->layout->add_js_in_page(array('data'=> $this->load->view('index/js', '', TRUE), 'comment' => 'INDEX FUNCTIONS'));
		$this->layout->view('index/index');
	}




}

?>