<?php
/**
 *
 *
 *
 *
 *
 *
 * */

class Widget {

	protected $_widget_view_paths = array();
	protected $_widget_cached_vars = array();
	protected $_widget_ob_level;

	protected $_default_attributes = array();
	protected $_custom_attributes = array();

	public function __construct() {

		$this -> _init();
	}

	public function initialize($config) {

		if (count($config) > 0) {

			foreach ($config as $key => $value) {
				$this -> _custom_attributes['data-widget-' . $key] = $value;
			}

		}

	}

	public function _init() {

		$this -> _default_attributes['data-widget-colorbutton'] = 'false';
		$this -> _default_attributes['data-widget-editbutton'] = 'false';
		$this -> _default_attributes['data-widget-togglebutton'] = 'false';
		$this -> _default_attributes['data-widget-deletebutton'] = 'false';
		$this -> _default_attributes['data-widget-fullscreenbutton'] = 'false';
		$this -> _default_attributes['data-widget-custombutton'] = 'false';
		$this -> _default_attributes['data-widget-collapsed'] = 'false';
		$this -> _default_attributes['data-widget-sortable'] = 'false';
		$this -> _default_attributes['data-widget-icon'] = '';

		$this -> _widget_ob_level = ob_get_level();
		$class = strtolower(str_replace('_widget', '', get_class($this)));
		$this -> _widget_view_paths = array(APPPATH . 'widgets/' . $class . '/views/' => TRUE);

	}

	public function view($view, $vars = array(), $return = FALSE) {
		//echo "hello world";

		//echo $view; exit();
		return $this -> _load(array('_ci_view' => $view, '_ci_vars' => $vars, '_ci_return' => $return));

	}

	protected function _load($_ci_data) {

		foreach (array('_ci_view', '_ci_vars', '_ci_path', '_ci_return') as $_ci_val) {
			$$_ci_val = (!isset($_ci_data[$_ci_val])) ? FALSE : $_ci_data[$_ci_val];
		}

		$file_exists = FALSE;

		// Set the path to the requested file
		if ($_ci_path != '') {
			$_ci_x = explode('/', $_ci_path);
			$_ci_file = end($_ci_x);
		} else {
			$_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
			$_ci_file = ($_ci_ext == '') ? $_ci_view . '.php' : $_ci_view;

			//$_folder = $controller == FALSE ? $this->_ft_layout_view_paths : $this->_ft_module_view_paths;

			$_folder = $this -> _widget_view_paths;

			//print_r($_folder);

			foreach ($_folder as $view_file => $cascade) {

				if (file_exists($view_file . $_ci_file)) {
					$_ci_path = $view_file . $_ci_file;
					$file_exists = TRUE;
					break;
				}

				if (!$cascade) {
					break;
				}
			}
		}

		if (!$file_exists && !file_exists($_ci_path)) {
			show_error('>Unable to load the requested file: ' . $_ci_file);
		}

		// This allows anything loaded using $this->load (views, files, etc.)
		// to become accessible from within the Controller and Model functions.

		$_ci_CI = &get_instance();

		foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var) {
			if (!isset($this -> $_ci_key)) {
				$this -> $_ci_key = &$_ci_CI -> $_ci_key;
			}
		}

		/*
		 * Extract and cache variables
		 *
		 * You can either set variables using the dedicated $this->load_vars()
		 * function or via the second parameter of this function. We'll merge
		 * the two types and cache them so that views that are embedded within
		 * other views can have access to these variables.
		 */
		if (is_array($_ci_vars)) {
			$this -> _widget_cached_vars = array_merge($this -> _widget_cached_vars, $_ci_vars);
		}
		extract($this -> _widget_cached_vars);

		/*
		 * Buffer the output
		 *
		 * We buffer the output for two reasons:
		 * 1. Speed. You get a significant speed boost.
		 * 2. So that the final rendered template can be
		 * post-processed by the output class.  Why do we
		 * need post processing?  For one thing, in order to
		 * show the elapsed page load time.  Unless we
		 * can intercept the content right before it's sent to
		 * the browser and then stop the timer it won't be accurate.
		 */
		ob_start();

		// If the PHP installation does not support short tags we'll
		// do a little string replacement, changing the short tags
		// to standard PHP echo statements.

		if ((bool)@ini_get('short_open_tag') === FALSE AND config_item('rewrite_short_tags') == TRUE) {
			echo eval('?>' . preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
		} else {
			include ($_ci_path);
			// include() vs include_once() allows for multiple views with the same name
		}

		log_message('debug', 'File loaded: ' . $_ci_path);

		// Return the file data if requested
		if ($_ci_return === TRUE) {
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		/*
		 * Flush the buffer... or buff the flusher?
		 *
		 * In order to permit views to be nested within
		 * other views, we need to flush the content back out whenever
		 * we are beyond the first level of output buffering so that
		 * it can be seen and included properly by the first included
		 * template and any subsequent ones. Oy!
		 *
		 */
		if (ob_get_level() > $this -> _widget_ob_level + 1) {
			ob_end_flush();
		} else {
			$_ci_CI -> output -> append_output(ob_get_contents());
			@ob_end_clean();
		}

	}

	public function get($id, $title, $content, $well = false, $no_padding = false, $tabs = array()) {

		$attr = count($this -> _custom_attributes) > 0 ? array_merge($this -> _default_attributes, $this -> _custom_attributes) : $this -> _default_attributes;

		$_html = '';

		$extended_class = $well == true ? 'well' : '';

		$time = time();

		$_html .= '<div id="' . $id . $time . '"  class="jarviswidget ' . $extended_class . '" ';

		foreach ($attr as $key => $value) {

			if ($value != 'true') {
				$_html .= $key . '="' . $value . '"';
			}

		}

		$_html .= ' >';

		//HEADER
		$_html .= '<header>';

		

		if (isset($attr['data-widget-icon']) && $attr['data-widget-icon'] != '') {
			$_html .= '<span class="widget-icon"><i class="' . $attr['data-widget-icon'] . '"></i></span>';
		}

		$_html .= '<h2>' . $title . '</h2>';
		
		
		if(count($tabs) > 0){
			
			$count = 1;
			$_html .= '<ul id="'.$id.$time.'-tab" class="nav nav-tabs pull-right">';
			foreach($tabs as $tab){
				
				
				$class = $count == 1 ? 'active' : '';
				
				
				$icon = isset($tab['icon']) ? '<i class="fa fa-lg '.$tab['icon'].'"></i>' : '';
				$href = isset($tab['href']) ? '#'.$tab['href'] : '#';
				
				
				
				$_html .= '<li class="'.$class.'">';
				$_html .= '<a data-toggle="tab" href="'.$href.'">'.$icon.'<span class="hidden-mobile hidden-tablet">'.$tab['name'].'</span></a>';
				$_html .= '</li>';
				$count++;
			}
			$_html .= '</ul>';
		}
		
		 
		$_html .= '</header>';

		$_html .= '<div>';

		$_html .= '<div class="jarviswidget-editbox"><!-- This area used as dropdown edit box --></div>';

		//BODY
		$extended_class = $no_padding == true ? ' no-padding' : '';
		$_html .= '<div class="widget-body ' . $extended_class . '">';

		$_html .= $content;
		$_html .= '</div>';

		$_html .= '</div>';
		$_html .= '</div>';

		return $this -> _do_compression($_html);

	}

	protected function _do_compression($string) {

		$buffer = $string;

		$search = array('/\n/', // replace end of line by a space
		'/\>[^\S ]+/s', // strip whitespaces after tags, except space
		'/[^\S ]+\</s', // strip whitespaces before tags, except space
		'/(\s)+/s'	// shorten multiple whitespace sequences
		);

		$replace = array(' ', '>', '<', '\\1');

		return preg_replace($search, $replace, $buffer);

	}

}
?>