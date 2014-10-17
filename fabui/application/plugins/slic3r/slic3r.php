<?php
/*
 Plugin Name: Slic3r
 Plugin URI: http://slic3r.org/
 Version: 1.0
 Description: A simple web UI of the famous Gcode converter.<br> Slic3r is the tool you need to convert a digital 3D model into printing instructions for your 3D printer. It cuts the model into horizontal slices (layers), generates toolpaths to fill them and calculates the amount of material to be extruded
 Author: FABteam - Alessandro Ranellucci
 Author URI: http://www.thingiverse.com/hudbrog/designs
 Plugin Slug: gcodeviewer
 Icon: fa-database
 */
class Slic3r extends Plugin {

	public function __construct() {
		
		parent::__construct();

	}

	public function index() {

		$this -> layout -> view('index');
	}

}
