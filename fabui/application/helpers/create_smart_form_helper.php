<?php
/*
 if ( ! function_exists('create_section'))
 {
function create_section($section)
{




$_label = isset($section['label']) ? $section['label'] : '';




if(!isset($section['fieldset']))
	return;

if(count($section['fieldset']) <= 0)
	return;

$_fieldset = $section['fieldset'];


$_html = '<!-- '.$_label.' -->';

$_html .= '<div class="margin-bottom-10">';

$_html .= '<h4>'.$_label.'</h4>';

$_html .= '<div class="padding-10">';

$_html .= '<div class="col-sm-12">';

$_html .= '<form class="form-horizontal">';


foreach($_fieldset as $f){

$_html .= create_fieldset($f);


}













$_html .= '</form>';

$_html .= '</div>';

$_html .= '</div>';

$_html .= '</div>';




return $_html;



}
}




function create_fieldset($fieldset){

//print_r($fieldset);

$html = '<fieldset>';

$html .= '<legend>'.$fieldset['legend'].'</legend>';

foreach($fieldset['fields'] as $field){

$html .= create_field($field);
}

$html .= '</fieldset>';


return $html;

}




function create_field($field){

$_html = '<div class="form-group">';


if(isset($field['label']) && $field['label'] != ''){
	$_html .= '<label class="col-md-2 control-label">'.$field['label'].'</label>';
}


$_html .= '<div class="col-md-10">';



$_class = isset($field['class']) ? $field['class'] : '';

switch($field['type']){

case 'text':
$_html .= '<input id="'.$field['id'].'" name="'.$field['name'].'" class="form-control '.$_class.'" type="'.$field['type'].'">';
break;
case 'checkbox':
$_html .= '<div class="checkbox"><label><input id="'.$field['id'].'" name="'.$field['name'].'" type="'.$field['type'].'" class="checkbox '.$_class.'" > <span> </span></label></div>';
break;
case 'select':
$_html .= form_dropdown($field['id'], $field['options'], '', 'class="form-control"');
break;
case 'textarea':
$_html .= '<textarea  id="'.$field['id'].'" name="'.$field['name'].'" class="form-control '.$_class.'"></textarea>';
break;
	

}





$_html .= '</div>';



$_html .= '</div>';


return $_html;

}
*/


function craeate_section($id_section){

	$_ci =& get_instance();

	//carico X class database
	$_ci->load->database();
	$_ci->load->model('printsettings');


	$section = $_ci->printsettings->get_section_by_id($id_section);
		
	
	
	
	
	$_html = '<div class="row">';

	$_html .= '<div class="col-sm-12">';
	
	
	
	$_html .= '<div class="accordion">';
	

	
	$_html .= create_groups($id_section);
	
	
	$_html .= '</div>';
	
	

	$_html .= '</div>';
	
	$_html .= '</div>';



	return $_html;


}



function create_groups($id_section){
	
	$_ci =& get_instance();
	
	//carico X class database
	$_ci->load->database();
	$_ci->load->model('printsettings');
	
	
	$groups = $_ci->printsettings->get_groups($id_section);
	
	$_html = '';
	
	foreach($groups as $group){
		
		
		
		$_html .= '<div class="margin-bottom-10">';
		
		$_html .= '<h4>'.$group->group_name.'</h4>';
		
		$_html .= '<div class="padding-10">';
		
		
		
		$_html .= '<div class="col-sm-12">';
		
		$_html .= create_fieldset($id_section, $group->id);
		
		
		
		
		$_html .= '</div>';
		
		$_html .= '</div>';
		
		$_html .= '</div>';
		
		
	}
	
	
	return $_html;
	
	
}




function create_fieldset($id_section, $id_group){
	
	$_ci =& get_instance();
	
	//carico X class database
	$_ci->load->database();
	$_ci->load->model('printsettings');
	
	$fieldsets = $_ci->printsettings->get_fieldsets($id_section, $id_group);
	
	
	$_html = '<!-- form -->';
	$_html .= '<form class="form-horizontal">';
	
	foreach($fieldsets as $fieldset){
		
		$_html .= '<fieldset>';
		
		$_html .= '<legend>'.$fieldset->fieldset_name.'</legend>';
		
		$_html .= create_fields($id_section, $id_group, $fieldset->id);
		
		
		$_html .= '</fieldset>';
		
		
	}
	
	
	$_html .= '</form>';
	
	return $_html;
	
	
}





function create_fields($id_section, $id_group, $id_fieldset){
	

	$_ci =& get_instance();
	
	//carico X class database
	$_ci->load->database();
	$_ci->load->model('printsettings');
	
	
	$fields = $_ci->printsettings->get_fields($id_section, $id_group, $id_fieldset);
	
	$_html = '';
	
	foreach($fields as $field){
		
		
		
		$_html .= '<div class="form-group">';
		
		if($field->field_label != ''){
			$_html .= '<label class="col-md-2 control-label">'.$field->field_label.'</label>';
		}
		
		
		
		$_html .= '<div class="col-md-10">';
		
		
		
		$_class = $field->field_class;
		
		switch($field->field_type){
		
			case 'text':
				$_html .= '<input id="'.$field->field_id.'" name="'.$field->field_name.'" class="form-control '.$_class.'" type="'.$field->field_type.'">';
				break;
			case 'checkbox':
				$_html .= '<div class="checkbox"><label><input id="'.$field->field_id.'" name="'.$field->field_name.'" type="'.$field->field_type.'" class="checkbox '.$_class.'" > <span> </span></label></div>';
				break;
			case 'select':
				$_html .= form_dropdown($field->field_id, '', '', 'class="form-control"');
				break;
			case 'textarea':
				$_html .= '<textarea  id="'.$field->field_id.'" name="'.$field->field_name.'" class="form-control '.$_class.'"></textarea>';
				break;
		
		
		}
		
		
		
		
		
		$_html .= '</div>';
		
		$_html .= '</div>';
		
		
		
	}
	
	
	
	return $_html;
	
}
