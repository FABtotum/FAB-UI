<?php

/**
 * 
 * @param unknown $id
 * @param unknown $title
 * @param unknown $attributes
 * @param unknown $content
 * @return string
 */
function widget($id, $title,  $attributes = array(), $content, $well = false, $no_padding = false, $dark = false, $header_toolbar = ''){
	
	$_default_attributes['data-widget-colorbutton']      = 'false';
	$_default_attributes['data-widget-editbutton']       = 'false';
	$_default_attributes['data-widget-togglebutton']     = 'false';
	$_default_attributes['data-widget-deletebutton']     = 'false';
	$_default_attributes['data-widget-fullscreenbutton'] = 'true';
	$_default_attributes['data-widget-custombutton']     = 'false';
	$_default_attributes['data-widget-collapsed']        = 'false';
	$_default_attributes['data-widget-sortable']         = 'false';
	$_default_attributes['data-widget-icon']             = '';
	
	
	
	
	$attr = is_array($attributes) ? array_merge($_default_attributes, $attributes) : $_default_attributes;
	

	//print_r($attributes);
	//print_r($attr);
	
	$_html = '';
	
	
	$extended_class = $well == true ? 'well' : '';
	
    $extended_class .= $dark == true ? ' jarviswidget-color-darken ' : '' ;
    
	$_html .= '<div id="'.$id.'"  class="jarviswidget  '.$extended_class.'" ';
	
	foreach($attr as $key => $value){
		
		if($value != 'true'){
			$_html .= $key.'="'.$value.'"';
		}
		
		
	}
	
	$_html .= ' >';
	
	//HEADER
	$_html .= '<header>';
	
	if(isset($attr['data-widget-icon']) && $attr['data-widget-icon'] != ''){
		$_html .= '<span class="widget-icon"><i class="'.$attr['data-widget-icon'].'"></i></span>';
	}
	
	$_html .= '<h2>'.$title.'</h2>';
	
	
	if($header_toolbar != ''){
		$_html .= $header_toolbar;
	}
	
	$_html .= '</header>';
	
	
	$_html .= '<div>';
		
	$_html .= '<div class="jarviswidget-editbox"> <!-- This area used as dropdown edit box --> </div>';
	
	//BODY
	$extended_class = $no_padding == true ? ' no-padding' : '';
	$_html .= '<div class="widget-body '.$extended_class.'">';
    
    //$_html .= '<div class="widget-body-toolbar"></div>';
	
	$_html .= $content;
	
	$_html .= '</div>';
	
	
	
	$_html .= '</div>';
	
	
	$_html .= '</div>';
	
	
	return $_html;
	
}






function tab($id, $items = array(), $active = 1){
	
	$_html = '';
	
	
	$_html .= '<ul id="header-'.$id.'" class="nav nav-tabs bordered">';
	
	
	$counter = 1;
	foreach($items as $header){
		
		$_class = $active == $counter ? 'active' : '';
		
		$_html .= '<li class="'.$_class.'" ><a href="#'.$header['reference'].'" data-toggle="tab">'.$header['title'].'</a></li>';

		$counter++;
	}
		
	$_html .= '</ul>';
	
	
	$_html .= '<div id="content-'.$id.'" class="tab-content padding-10">';
	
	
	$counter = 1;
	foreach($items as $content){
		
		
		$_class = $active == $counter ? 'active in' : '';
		
		$_html .= '<div class="tab-pane fade '.$_class.'" id="'.$content['reference'].'">';
		
		$_html .= $content['content'];
		
		$_html .= '</div>';
		
		$counter++;
	}
	
	
	$_html .= '</div>';
	
	return $_html;
	
	
}
















