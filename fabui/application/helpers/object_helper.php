<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('file_header_toolbar'))
{
	/**
	 * OBJECT ->
	 * FILE ->
	 * VIEW ->
	 */
	function file_header_toolbar($object, $file, $view=''){
		
		
		$CI =& get_instance();
		$CI -> config -> load('fabtotum', TRUE);
		
		$printables_files = $CI -> config -> item('printables_files', 'fabtotum');
		$preview_files    = $CI -> config -> item('preview_files', 'fabtotum');
		
		$edit_button           = '';
		$action_button         = '';
		$download_button       = '';
		$preview_button        = '';
		$stats_button          = '';
		$back_to_object_button = '';
		
		$html = '<div class="row margin-bottom-10"><div class="col-sm-12">';
		
		//edit
		if($view != 'edit') $edit_button = '<a  style="margin-left:5px;" rel="tooltip" data-placement="bottom" data-original-title="Edit the file" href="'.site_url('objectmanager/file/view/'.$object->id.'/'.$file->id).'" class="btn btn-primary details-button pull-right"><i class="fa fa-pencil"></i> <span class="hidden-xs">Edit</span></a>';
		
		
		//print-mill
		if(in_array(strtolower($file->file_ext),$printables_files)){
			
			switch($file->print_type){
				case 'additive':
					$type = 'print';
					break;
				case 'subtractive':
					$type = 'mill';
					break;
				case 'laser':
					$type = 'laser';
					break;
			}
			$action_button = '<a style="margin-left:5px;" rel="tooltip" data-placement="bottom" data-original-title="'.ucfirst($type).' this file" href="'.site_url('make/'.$type.'?obj='.$object->id.'&file='.$file->id).'" class="btn btn-success pull-right"><i class="fa fa-play fa-rotate-90"></i> <span class="hidden-xs">'.ucfirst($type).'</span></a>';
			if($view != 'stats') $stats_button = '<a rel="tooltip" data-placement="bottom" data-original-title="View file stats" style="margin-left:5px;"  class="btn btn-warning pull-right" href="'.site_url("objectmanager/file/stats/".$object->id.'/'.$file->id).'"><i class="fa fa-area-chart"></i> <span class="hidden-xs">Stats</span></a>';
		}
		
		//download
		$download_button = '<a data-placement="bottom" href="'.site_url('objectmanager/download/file/'.$file->id).'" rel="tooltip" data-original-title="Save data on your computer. You can use it in the third party software." style="margin-left:5px;" class="btn btn-info txt-color-white pull-right"><i class="fa fa fa-download"></i>  <span class="hidden-xs">Download</span> </a>';
		
		//preview
		if($view != 'preview')
			if(in_array(strtolower($file->file_ext), $preview_files) && $type == 'print'){
				$preview_button = '<a data-placement="bottom" href="'.site_url('objectmanager/file/preview/'.$object->id.'/'.$file->id).'" rel="tooltip" data-original-title="A web-based 3D viewer for GCode files." style="margin-left:5px;" class="btn bg-color-purple txt-color-white pull-right"><i class="fa fa-eye"></i> <span class="hidden-xs">Preview</span> </a>';
			}
		
		//stats
		
		
				
		//back to object		
		$back_to_object_button = '<a  style="margin-left:5px;" href="'.site_url("objectmanager/edit/".$object->id).'" class="btn btn-default pull-right"> <i class="fa fa-arrow-left"></i> Back to object</a>';
		
		$html .= $stats_button.$preview_button.$download_button.$action_button.$edit_button.$back_to_object_button;
		
		$html .= '</div></div>';
		
		return $html;
		
	}
	
	
}
