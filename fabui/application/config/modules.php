<?php
$modules[] = array('name' => 'dashboard',     'label'=>'Dashboard',     'menu' => TRUE, 'icon' => '<i class="fa fa-lg fa-fw  fa-home"></i>',    'block' => FALSE);


$make_sons[] = array('name' => 'print', 'label'=>'Print', 'menu' => TRUE, 'icon' => '',   'block' => TRUE);
$make_sons[] = array('name' => 'mill',  'label'=>'Mill',  'menu' => TRUE, 'icon' => '',   'block' => TRUE);
$make_sons[] = array('name' => 'scan',  'label'=>'Scan',  'menu' => TRUE, 'icon' => '',    'block' => TRUE);


$modules[] = array('name' => 'make',   'label'=>'Make',   'menu' => TRUE, 'icon' => '<i style="vertical-align:0% !important;" class="fa fa-lg fa-fw fa-play rotate-90  txt-color-blue"></i>', 'block' => FALSE, 'sons' => $make_sons);

//fa-caret-square-o-down

//$modules[] = array('name' => 'scan',          'label'=>'Scan',          'menu' => TRUE, 'icon' => '<i class="fab-lg fab-fw  icon-fab-scan"></i>',    'block' => TRUE);
//$modules[] = array('name' => 'create',        'label'=>'Create',        'menu' => TRUE, 'icon' => '<i class="fab-lg fab-fw  icon-fab-print"></i>',   'block' => TRUE);
$modules[] = array('name' => 'jog',           'label'=>'Jog',           'menu' => TRUE, 'icon' => '<i class="fab-lg fab-fw  icon-fab-jog"></i>',     'block' => TRUE);
$modules[] = array('name' => 'objectmanager', 'label'=>'Objectmanager', 'menu' => TRUE, 'icon' => '<i class="fa fa-lg fa-fw fa-folder-open"></i>',   'block' => TRUE);

$_maintenance_sons[] = array('name' => 'head',       'label'=> 'Head',       'icon' => '<i class="fa fa-lg fa-fw  fa-toggle-down"></i>');
// maintenance sub-items menu
$_maintenance_sons[] = array('name' => 'spool',             'label'=> 'Spool',             'icon' => '<i class="fa fa-lg fa-fw  fa-circle-o-notch"></i>');
$_maintenance_sons[] = array('name' => 'feeder',            'label'=> 'Feeder',            'icon' => '<i class="fa fa-lg fa-fw  fa-cog"></i>');
$_maintenance_sons[] = array('name' => '4-axis',            'label'=> '4th Axis',          'icon' => '<i class="fa fa-lg fa-fw  fa-arrows-h"></i>');
$_maintenance_sons[] = array('name' => 'bed-calibration',   'label'=> 'Bed Calibration',   'icon' => '<i class="fa fa-lg fa-fw  fa-arrows-h"></i>');
$_maintenance_sons[] = array('name' => 'probe-calibration', 'label'=> 'Probe Calibration', 'icon' => '<i class="fa fa-lg fa-fw fa-crosshairs"></i>');
//$_maintenance_sons[] = array('name' => 'self-test',         'label'=> 'Self Test',         'icon' => '<i class="fa fa-lg fa-fw  fa-dashboard"></i>');
$_maintenance_sons[] = array('name' => 'first-setup',       'label'=> 'First Setup',       'icon' => '<i class="fa fa-lg fa-fw  fa-magic"></i>');





$modules[] = array('name' => 'maintenance',   'label'=>'Maintenance',   'menu' => TRUE, 'icon' => '<i class="fa fa-lg fa-fw  fa-wrench"></i>', 'block' => FALSE, 'sons' => $_maintenance_sons);
$modules[] = array('name' => 'settings',      'label'=>'Settings',      'menu' => TRUE, 'icon' => '<i class="fa fa-lg fa-fw  fa-cogs"></i>',                  'block' => FALSE);
$modules[] = array('name' => 'updates',       'label'=>'Updates',       'menu' => TRUE, 'icon' => '<i class="fa fa-lg fa-fw  fa-refresh"></i>',               'block' => FALSE);
$modules[] = array('name' => 'plugin',        'label'=>'Plugins',       'menu' => TRUE, 'icon' => '<i class="fa fa-lg fa-fw  fa-plug"></i>',                  'block' => FALSE);
$modules[] = array('name' => 'support',       'label'=>'Support',       'menu' => TRUE, 'icon' => '<i class="fa fa-lg fa-fw  fa-life-ring"></i>',             'block' => TRUE);