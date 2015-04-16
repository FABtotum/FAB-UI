<?php

$layout['_layout_title'] = 'FAB UI beta';

/*
 * Default METATAG
 * 
 */

$layout['_meta'][] = array('name'=>'author',           'content'=>'FABteam', 'comment'=>'');
$layout['_meta'][] = array('name'=>'viewport',         'content'=>'width=device-width, initial-scale=1.0', 'comment'=>'');
$layout['_meta'][] = array('name'=>'HandheldFriendly', 'content'=>'true', 'comment'=>'');

$layout['_meta'][] = array('name'=>'apple-mobile-web-app-capable', 'content'=>'yes', 'comment'=>'');
$layout['_meta'][] = array('name'=>'apple-mobile-web-app-status-bar-style', 'content'=>'black', 'comment'=>'');

/*
 * Default CSS to include on all pages
*/
/** TEMPLATE SMART ADMIN DEFAULT CSS */
$layout['_css'][] = array('src' => 'application/layout/assets/css/bootstrap.min.css', 'comment'=> '', 'external' => FALSE);
$layout['_css'][] = array('src' => 'application/layout/assets/css/font-awesome.min.css', 'comment'=> '', 'external' => FALSE, 'font' =>true);


$layout['_css'][] = array('src' => 'application/layout/assets/css/smartadmin-production-plugins.min.css', 'comment'=> '', 'external' => FALSE);
$layout['_css'][] = array('src' => 'application/layout/assets/css/smartadmin-production.min.css', 'comment'=> '', 'external' => FALSE);
$layout['_css'][] = array('src' => 'application/layout/assets/css/smartadmin-skins.min.css', 'comment'=> '', 'external' => FALSE);
$layout['_css'][] = array('src' => 'application/layout/assets/css/demo.min.css', 'comment'=> '', 'external' => FALSE);


$layout['_css'][] = array('src' => 'application/layout/assets/css/font-fabtotum.css', 'comment'=> 'FONT FABTOTUM', 'external' => FALSE, 'font' =>true);
$layout['_css'][] = array('src' => 'application/layout/assets/js/plugin/magnific-popup/magnific-popup.css', 'comment'=> '', 'external' => FALSE);

$layout['_css'][] = array('src' => 'application/layout/assets/css/fonts.css', 'comment'=> 'Google Font', 'external' => FALSE, 'font' =>true);
$layout['_css'][] = array('src' => 'application/layout/assets/css/fabtotum_style.css', 'comment'=> 'Fabtotum', 'external' => FALSE);

/*
 * Default JS to include on all pages
 */

$layout['_header_js'][] = array('src'=>'application/layout/assets/js/libs/jquery-2.1.1.min.js', 'comment' => 'jQuery', 'external' => FALSE);
$layout['_header_js'][] = array('src'=>'application/layout/assets/js/libs/jquery-ui-1.10.3.min.js', 'comment' => 'jQuery UI', 'external' => FALSE);

$layout['_js'][] = array('src'=>'application/layout/assets/js/app.config.js', 'comment' => 'jQuery UI', 'external' => FALSE);


$layout['_js'][] = array('src'=>'application/layout/assets/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js', 'comment' => 'jQuery TOUCH', 'external' => FALSE);
$layout['_js'][] = array('src'=>'application/layout/assets/js/bootstrap/bootstrap.min.js', 'comment' => 'BOOTSTRAP JS', 'external' => FALSE);
$layout['_js'][] = array('src'=>'application/layout/assets/js/notification/SmartNotification.min.js', 'comment' => 'CUSTOM NOTIFICATION', 'external' => FALSE);
$layout['_js'][] = array('src'=>'application/layout/assets/js/smartwidgets/jarvis.widget.min.js', 'comment' => 'JARVIS WIDGETS', 'external' => FALSE);
$layout['_js'][] = array('src'=>'application/layout/assets/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js', 'comment' => 'EASY PIE CHARTS', 'external' => FALSE);
$layout['_js'][] = array('src'=>'application/layout/assets/js/plugin/msie-fix/jquery.mb.browser.min.js', 'comment' => 'browser msie issue fix', 'external' => FALSE);
$layout['_js'][] = array('src'=>'application/layout/assets/js/plugin/fastclick/fastclick.min.js', 'comment' => 'SmartClick: For mobile devices', 'external' => FALSE);


$layout['_js'][] = array('src'=>'application/layout/assets/js/plugin/magnific-popup/jquery.magnific-popup.min.js', 'comment' => 'magnific-popup', 'external' => FALSE);

$layout['_js'][] = array('src'=>'application/layout/assets/js/demo.min.js', 'comment' => 'Demo', 'external' => FALSE);
$layout['_js'][] = array('src'=>'application/layout/assets/js/app.min.js', 'comment' => 'Demo', 'external' => FALSE);
$layout['_js'][] = array('src'=>'application/layout/assets/js/fabwebsocket.js', 'comment' => 'fabtotum websocket', 'external' => FALSE);
$layout['_js'][] = array('src'=>'application/layout/assets/js/fabtotum.js', 'comment' => 'fabtotum utilities', 'external' => FALSE);


