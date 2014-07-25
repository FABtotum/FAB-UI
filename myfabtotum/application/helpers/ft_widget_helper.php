<?php


if ( ! function_exists('widget_info'))
{
	function widget_info($_widget){
		
		
		if(is_file(APPPATH.'widgets/'.$_widget.'/'.$_widget.'.php')){

			
			$fp = fopen(APPPATH.'widgets/'.$_widget.'/'.$_widget.'.php', 'r');
			
			$_widget_info = fread($fp, 8192);
			
			fclose($fp);
			
			preg_match ( '|Widget Name:(.*)$|mi', $_widget_info, $name );
			preg_match ( '|Widget URI:(.*)$|mi',  $_widget_info, $uri );
			preg_match ( '|Version:(.*)|i',       $_widget_info, $version );
			preg_match ( '|Description:(.*)$|mi', $_widget_info, $description );
			preg_match ( '|Author:(.*)$|mi',      $_widget_info, $author_name );
			preg_match ( '|Author URI:(.*)$|mi',  $_widget_info, $author_uri );
			preg_match ( '|Widget Slug:(.*)$|mi', $_widget_info, $plugin_slug );
			
			foreach ( array ('name', 'uri', 'version', 'description', 'author_name', 'author_uri', 'plugin_slug' ) as $field ) {
				if (! empty ( ${$field} ))
					${$field} = trim ( ${$field} [1] );
				else
					${$field} = '';
			}
			
			$_widget_info = array ('name' => $name, 'title' => $name, 'plugin_uri' => $uri, 'description' => $description, 'author' => $author_name, 'author_uri' => $author_uri, 'version' => $version );
			
			return $_widget_info;
			
		}
		
	}

}


?>