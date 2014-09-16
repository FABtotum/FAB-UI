<?php

$_dashboard_sons[] = array('name' => 'setup', 'label' => 'Setup');

$modules[] = array('name' => 'dashboard',     'label'=>'Dashboard',     'menu' => TRUE, 'icon' => '<i class="fab-lg fab-fw  icon-fab-home"></i>',    'block' => FALSE, 'sons' => $_dashboard_sons);
$modules[] = array('name' => 'scan',          'label'=>'Scan',          'menu' => TRUE, 'icon' => '<i class="fab-lg fab-fw  icon-fab-scan"></i>',    'block' => TRUE);
$modules[] = array('name' => 'create',        'label'=>'Create',        'menu' => TRUE, 'icon' => '<i class="fab-lg fab-fw  icon-fab-print"></i>',   'block' => TRUE);
$modules[] = array('name' => 'jog',           'label'=>'Jog',           'menu' => TRUE, 'icon' => '<i class="fab-lg fab-fw  icon-fab-jog"></i>',     'block' => TRUE);
$modules[] = array('name' => 'objectmanager', 'label'=>'Objectmanager', 'menu' => TRUE, 'icon' => '<i class="fab-lg fab-fw  icon-fab-manager"></i>', 'block' => TRUE);
$modules[] = array('name' => 'settings',      'label'=>'Settings',      'menu' => TRUE, 'icon' => '<i class="fa fa-lg fa-fw  fa-cogs"></i>',         'block' => FALSE);
$modules[] = array('name' => 'updates',       'label'=>'Updates',       'menu' => TRUE, 'icon' => '<i class="fa fa-lg fa-fw  fa-refresh"></i>',      'block' => FALSE);

