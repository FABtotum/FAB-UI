<?php

////////////////////////////
// LAYERS AND PERIMETERS //
///////////////////////////

$_fields     = array();
$_fields[]   = array('name'=> 'layer_height', 'id'=>'layer_height', 'type'=>'text', 'label' => 'Layer height: (mm)');
$_fields[]   = array('name'=> 'first_layer_height', 'id'=>'first_layer_height', 'type'=>'text', 'label' => 'First layer height: (mm or %)');

$_fieldset_layers[] = array('legend' => 'Layer height', 'fields'=> $_fields);

$_fields     = array();
$_fields[]   = array('name'=> 'perimeters', 'id'=>'perimeters', 'type'=>'text', 'label' => 'Perimeters (minimum):', 'class'=>'spinner');
$_fields[]   = array('name'=> 'randomize_starting_points', 'id'=>'randomize_starting_points', 'type'=>'checkbox', 'label' => 'Randomize starting points');
$_fields[]   = array('name'=> 'generate_extra_perimeters_when_needed', 'id'=>'generate_extra_perimeters_when_needed', 'type'=>'checkbox', 'label' => 'Generate extra perimeters when needed: ');

$_fieldset_layers[] = array('legend' => 'Vertical shells', 'fields'=> $_fields);

$_fields     = array();
$_fields[]   = array('name'=> 'solid_layers_top', 'id'=>'solid_layers_top', 'type'=>'text', 'label' => 'Solid layers top :', 'class'=>'spinner');
$_fields[]   = array('name'=> 'solid_layers_bottom', 'id'=>'solid_layers_bottom', 'type'=>'text', 'label' => 'Solid layers bottom :', 'class'=>'spinner');

$_fieldset_layers[] = array('legend' => 'Horizontal shells', 'fields'=> $_fields);

$_section_layers_and_perimeters = array('label'=>'Layers and Perimters', 'fieldset' =>$_fieldset_layers);











$_sections_print_settings[] = $_section_layers_and_perimeters;