<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('installed_plugins'))
{
	/**
	 * 
	 * @return all installed plugins
	 */
	function installed_plugins(){
		
		$CI =& get_instance();
		
		$CI->load->helper('directory');
		
		$_plugins_folders = directory_map(APPPATH.'plugins');
		
		$_installed_plugins = array();
		
		
		foreach($_plugins_folders as $_key => $_value){
			
			$_installed_plugins[] = array('plugin'=>$_key, 'folder' => APPPATH.'plugins/'.$_key);
			
		}
		
		return $_installed_plugins;
		
	}
	 
}


if ( ! function_exists('plugin_info'))
{
	function plugin_info($_plugin){
		
		
		if(is_file(APPPATH.'plugins/'.$_plugin.'/'.$_plugin.'.php')){

			
			$fp = fopen(APPPATH.'plugins/'.$_plugin.'/'.$_plugin.'.php', 'r');
			
			$_plugin_info = fread($fp, 8192);
			
			fclose($fp);
			
			preg_match ( '|Plugin Name:(.*)$|mi', $_plugin_info, $name );
			preg_match ( '|Plugin URI:(.*)$|mi',  $_plugin_info, $uri );
			preg_match ( '|Version:(.*)|i',       $_plugin_info, $version );
			preg_match ( '|Description:(.*)$|mi', $_plugin_info, $description );
			preg_match ( '|Author:(.*)$|mi',      $_plugin_info, $author_name );
			preg_match ( '|Author URI:(.*)$|mi',  $_plugin_info, $author_uri );
			preg_match ( '|Plugin Slug:(.*)$|mi', $_plugin_info, $plugin_slug );
			
			foreach ( array ('name', 'uri', 'version', 'description', 'author_name', 'author_uri', 'plugin_slug' ) as $field ) {
				if (! empty ( ${$field} ))
					${$field} = trim ( ${$field} [1] );
				else
					${$field} = '';
			}
			
			$_plugin_info = array ('name' => $name, 'title' => $name, 'plugin_uri' => $uri, 'description' => $description, 'author' => $author_name, 'author_uri' => $author_uri, 'version' => $version );
			
			return $_plugin_info;
			
		}
		
	}

}







if ( ! function_exists('is_plugin_active'))
{
	function is_plugin_active($plugin){
		
		$CI =& get_instance();
		
		if(!isset($CI->database)){
			
			$CI->load->database();
		}
		
		if(!isset($CI->plugin)){
			
			$CI->load->model('plugins');
			
		}
		
		return $CI->plugins->is_active($plugin);
		
	}

}

