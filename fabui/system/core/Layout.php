<?php
class FT_Layout {


	protected $_meta_tag = array();
	
	protected $_css_file = array();
	
	protected $_header_js_file = array();
	protected $_js_file  = array();

	protected $_css_in_page = array();
	protected $_js_in_page  = array();
	protected $_item_menu   = array();
	
	protected $_layout_title;
    
    protected $_skin;
	
	protected $_custom_ribbon;
	
	protected $_ft_layout_view_paths  = array();
	protected $_ft_module_view_paths  = array();
	protected $_ft_layout_cached_vars = array();
	protected $_ft_ob_level;
    
    protected $_compress = true;
	
	
	protected $_setup_wizard = false;

	
	protected $_ci;

	/**
	 * 
	 */
	public function __construct()
	{
		$this->_initialize();
	}
	
	
	/**
	 * 
	 */
	protected function _initialize(){
		
		$this->_ci =& get_instance();
		
		
		$this->_ft_ob_level          = ob_get_level();
		
		$this->_ft_layout_view_paths = array(APPPATH.'layout/views/' => TRUE);
		$this->_ft_module_view_paths = array(APPPATH.$this->_ci->_type.'s/'.lcfirst(get_class($this->_ci)).'/views/' => TRUE);
		
		
		// Load the config layout.php file.
		if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/layout.php'))
		{
			include(APPPATH.'config/'.ENVIRONMENT.'/layout.php');
		}
		elseif (is_file(APPPATH.'config/layout.php'))
		{
			include(APPPATH.'config/layout.php');
		
		}
		
		
		$this->set_layout_title($layout['_layout_title']);
		unset($layout['_layout_title']);
		
		
		//add the default META TAG
		foreach($layout['_meta'] as $meta){
			$this->add_meta($meta);
		}
		
		
		//add the default CSS Style
		foreach($layout['_css'] as $_css){
		
			$this->add_css_file($_css);
		}
		
		
		//add the default headr JS
		foreach($layout['_header_js'] as $_js){
		
			$this->add_header_js_file($_js);
		}
		
		
		//add the default JS
		foreach($layout['_js'] as $_js){
		
			$this->add_js_file($_js);
		}
		
		
		unset($layout['_css']);
		unset($layout['_js']);
		unset($layout['_header_js']);
		
		//add js in page for the template
		$this->add_js_in_page(array('data'=> $this->_ft_load(array('_ci_view' => 'js', '_ci_vars' => '' , '_ci_return' => TRUE)), 'Global Functions'));
		
		
		// Load the config modules.php file (L'elenco dei moduli)
		if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/modules.php'))
		{
			include(APPPATH.'config/'.ENVIRONMENT.'/modules.php');
		}
		elseif (is_file(APPPATH.'config/modules.php'))
		{
			include(APPPATH.'config/modules.php');
		
		}
		
		//add modules menu items
		foreach($modules as $_item){
			
			//if($_is_running)
			$_item['plugin'] = false;
			$this->add_item_menu($_item);
		}
		
		
		
		$this->_ci->load->database();
		
		
		if(!isset($_SESSION['plugins'])){
			
			$this->_ci->load->model('plugins');
			$_SESSION['plugins'] = $this->_ci->plugins->get_activeted_plugins();
		}
		
		
		
		foreach($_SESSION['plugins'] as $plugin){
			
			//print_r($plugin);
			$attributes = json_decode($plugin->attributes, TRUE);
				
			$this->add_item_menu(array('plugin'=>true, 'name' => $plugin->name, 'label' =>$attributes['title'], 'icon' => '<i class="fa fa-lg fa-fw '.$attributes['icon'].'"></i>', 'id' => $plugin->id));
		}
		
		
		
		unset($modules);
		
        
        if(isset($_SESSION['language']) && $_SESSION['language'] != ''){
            
            return $_SESSION['language'];
            
        }else{
            
            $this->_ci->load->model('configuration');
            $languages = json_decode($this->_ci->configuration->get_config_value('languages'),TRUE);
            $language = $this->_ci->configuration->get_config_value('language');     
            $_SESSION['language'] = $languages[$language];
        }
        
        //$this->_language = $_SESSION['language'];
		
		log_message('debug', "Loader Class Initialized");
		
	}




	public function view($view, $vars = array(), $return = FALSE)
	{



		//layout title 
		$data['_layout_title'] = $this->_layout_title;
		
		//load meta tag
		$data['_layout_meta_tag'] = $this->_load_meta_tag();
        
		//load theme skin
        $data['_skin'] = $this->_load_skin();
        
        //load language
        $data['_language'] = $_SESSION['language'];
		
		//load css files
		$data['_css_files'] = $this->_load_css();

		//load header js files
		$data['_header_js_files']  = $this->_header_load_js();
		
		//load js files (included on the bottom of the page)
		$data['_js_files']  = $this->_load_js();

		//load possible css in page
		$data['_css_in_page'] = $this->_load_css_in_page();

		//load possible js in page
		$data['_js_in_page'] = $this->_load_js_in_page();
		
		//load menu
		$data['_sidebar_menu_items'] = $this->_load_menu_items();
		
		//load breadcrumb
		$data['_breadcrumbs'] = $this->_load_bradcrumbs();

		//load page (module/plugin) content
		$data['_controller_view'] = $this->_ci->load->view($view, $vars, TRUE);
		
		//load possible ribbon
		$data['_custom_ribbon'] = $this->_custom_ribbon;
		
		//load wizard
		$data['_setup_wizard'] = $this->get_setup_wizard();


		return $this->_ft_load(array('_ci_view' => 'index', '_ci_vars' => $this->_ci_object_to_array($data), '_ci_return' => $return));
	}


	protected function _ft_load($_ci_data, $controller = FALSE)
	{
		// Set the default data variables
		
		foreach (array('_ci_view', '_ci_vars', '_ci_path', '_ci_return') as $_ci_val)
		{
			$$_ci_val = ( ! isset($_ci_data[$_ci_val])) ? FALSE : $_ci_data[$_ci_val];
		}

		$file_exists = FALSE;

		// Set the path to the requested file
		if ($_ci_path != '')
		{
			$_ci_x = explode('/', $_ci_path);
			$_ci_file = end($_ci_x);
		}
		else
		{
			$_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
			$_ci_file = ($_ci_ext == '') ? $_ci_view.'.php' : $_ci_view;



			$_folder = $controller == FALSE ? $this->_ft_layout_view_paths : $this->_ft_module_view_paths;


			foreach ($_folder as $view_file => $cascade)
			{
				if (file_exists($view_file.$_ci_file))
				{
					$_ci_path = $view_file.$_ci_file;
					$file_exists = TRUE;
					break;
				}

				if ( ! $cascade)
				{
					break;
				}
			}
		}

		if ( ! $file_exists && ! file_exists($_ci_path))
		{
			show_error('Unable to load the requested file: '.$_ci_file);
		}

		// This allows anything loaded using $this->load (views, files, etc.)
		// to become accessible from within the Controller and Model functions.

		$_ci_CI =& get_instance();

		foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var)
		{
			if ( ! isset($this->$_ci_key))
			{
				$this->$_ci_key =& $_ci_CI->$_ci_key;
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
		if (is_array($_ci_vars))
		{
			$this->_ft_layout_cached_vars = array_merge($this->_ft_layout_cached_vars, $_ci_vars);
		}
		extract($this->_ft_layout_cached_vars);

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

		if ((bool) @ini_get('short_open_tag') === FALSE AND config_item('rewrite_short_tags') == TRUE)
		{
			echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
		}
		else
		{
			include($_ci_path); // include() vs include_once() allows for multiple views with the same name
		}

		log_message('debug', 'File loaded: '.$_ci_path);

		// Return the file data if requested
		if ($_ci_return === TRUE)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
            return $this->_compress == true ? $this->_do_compression($buffer) : $buffer;
			//return $this->_do_compression($buffer);
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
		if (ob_get_level() > $this->_ft_ob_level + 1)
		{
			ob_end_flush();
		}
		else
		{
            $this->_compress == true ? 	$_ci_CI->output->append_output($this->_do_compression(ob_get_contents())) : $_ci_CI->output->append_output(ob_get_contents());
		
			@ob_end_clean();
		}
	}


	/**
	 * Object to Array
	 *
	 * Takes an object as input and converts the class variables to array key/vals
	 *
	 * @param	object
	 * @return	array
	 */
	protected function _ci_object_to_array($object)
	{
		return (is_object($object)) ? get_object_vars($object) : $object;
	}
	
	
	
	
	/**
	 * SET page TITLE
	 * @param $title
	 * 
	 */
	public function set_layout_title($title){
		$this->_layout_title = $title;
	}
	
	
	/**
	 * set custom ribbon
	 * @parama $custom
	 * 
	 */
	public function set_custom_ribbon($custom){
		$this->_custom_ribbon = $custom;
	}



	/**
	 * add css file
	 * @param #css_file
	 */
	public function add_css_file($css_file){
		$this->_css_file[] = $css_file;
	}

	
	/**
	 * add js file
	 * @param $js_file
	 */
	public function add_js_file($js_file){
		$this->_js_file[] = $js_file;
	}
	
	
	/**
	 * add header js file
	 * @param $js_file
	 */
	public function add_header_js_file($js_file){
		$this->_header_js_file[] = $js_file;
	}
	

	/**
	 * add css in page
	 * @param $css_in_page
	 */
	public function add_css_in_page($css_in_page){
		$this->_css_in_page[] = $css_in_page;
	}


	/**
	 * add js in page
	 * @param $js_in_page
	 */
	public function add_js_in_page($js_in_page){
		$this->_js_in_page[] = $js_in_page;
	}
	
	
	/**
	 * add item to menu
	 * @param $item_menu
	 */
	public function add_item_menu($item_menu){
		$this->_item_menu[] = $item_menu;
	}
	
	
	/**
	 * add meta tag
	 * @param $meta
	 */
	public function add_meta($meta){
		$this->_meta_tag[] = $meta;
	}

    
	
	/**
	 * set page compression
	 * @param $bool
	 */
    public function set_compress($bool){    
        $this->_compress = $bool;
    }
	
	
	/**
	 * load possible css in page
	 */
	protected function _load_css_in_page(){
		return $this->_load_object_in_page($this->_css_in_page);
	}


	protected function _load_js_in_page(){
		return $this->_load_object_in_page($this->_js_in_page);
	}


	
	/**
	 * generate html for css files inclusion
	 * @return $html
	 */
	protected function _load_css(){

        $html = '';

		foreach($this->_css_file as $css){
			if(isset($css['src']) && $css['src'] != ''){
				if(isset($css['comment']) && $css['comment'] != '')
					$html .= '<!-- '.$css['comment']. ' -->'.PHP_EOL;
				$_src = isset($css['external']) && $css['external'] == TRUE ?  $css['src'] : base_url().$css['src'].'?v='.$_SESSION['fabui_version'];
				$html .= '<link rel="stylesheet" type="text/css" media="screen" href="'.$_src.'">'.PHP_EOL;
			}
		}
		return $html;

	}


	/**
	 * generate html for js files inside <header>
	 * @return $html
	 */
	protected function _header_load_js(){
		$html = '<!-- JS HEADER -->';
		foreach($this->_header_js_file as $js){
			if(isset($js['src']) && $js['src'] != ''){
				if(isset($js['comment'])&& $js['comment'] != '')
					$html .= '<!-- '.$js['comment']. ' -->'.PHP_EOL;
				$_src = isset($js['external']) && $js['external'] == TRUE ?  $js['src'] : base_url().$js['src'].'?v='.$_SESSION['fabui_version'];
				$html .= '<script src="'.$_src.'"></script>'.PHP_EOL;
			}

		}
		return $html;

	}


	/**
	 * generate html for js files included on the bottom of the pages
	 */
	protected function _load_js(){
		
		$html = '';
		foreach($this->_js_file as $js){
			if(isset($js['src']) && $js['src'] != ''){
				if(isset($js['comment'])&& $js['comment'] != '')
					$html .= '<!-- '.$js['comment']. ' -->'.PHP_EOL;
				$_src = isset($js['external']) && $js['external'] == TRUE ?  $js['src'] : base_url().$js['src'].'?v='.$_SESSION['fabui_version'];
				$html .= '<script src="'.$_src.'"></script>'.PHP_EOL;
			}
		}

		return $html;

	}


	
	/**
	 * generate html 
	 */
	protected function _load_object_in_page($data){

		$html = '';
		foreach($data as $item){

			if(isset($item['data']) && $item['data'] != ''){

				$html .= isset($item['comment']) && $item['comment'] != '' ? '<!-- '.$item['comment'].' -->' : '';
				$html .= $item['data'];

			}

		}

		return $html;
	}

	
	
	/**
	 * generate html for sidebar menu
	 * @return $html
	 */
	protected function _load_menu_items(){
		
		$html = '';
		
		
		foreach($this->_item_menu as $item){
			
			$active = strtolower(get_class($this->_ci)) == $item['name'] ? 'active' : '';
	
			$html .= '<li class="'.$active.'">';			
			$block = isset($_item['block']) ? $_item['block'] : 1;
			
			$href = $item['plugin'] ? site_url('plugin/'.$item['name']) : site_url($item['name']);
			
			if(isset($item['sons'])){
				$href = '#';
			}
			
			$link = '<a data-controller="'.$item["name"].'" data-block="'.$block.'" data-href="'.$href.'" href="'.$href.'">';
			$html .= $link;
			$icon = '';
			
			if(isset($item['icon']) && $item['icon'] != '')
				$icon .= $item['icon'];
			
			$html .= $icon;
			$_label = isset($item['label']) && $item['label'] != '' ? $item['label'] : $item['name'];
			$html .= ' <span class="menu-item-parent">'.ucfirst($_label).'</span>';
			
			$html .= '</a>';
			
			
			//if are present sons
			if(isset($item['sons'])){

				$uri = $this->_ci->uri->segments;
				$html.= '<ul>';
				
				foreach($item['sons'] as $son){
					
					$active = isset($uri[2]) && $uri[2] == $son['name'] ? 'active' : '';
					$html .= '<li class="'.$active.'">';
					$href = site_url($item['name'].'/'.$son['name']);
					$html .= '<a data-href="'.$href.'"  href="'.$href.'">';
					
					if(isset($son['icon'])){
						$html .= $son['icon'].' ';
					}
					
					$html .= $son['label'];
					$html .= '</a>';
					$html .= '</li>';
					
				}
				$html.= '</ul>';
				
			}
			$html .= '</li>';
			
		}
		return $html;
		
	}
	
	
	/**
	 * generate html for breadcrumbs
	 * @return $html
	 */
	protected function _load_bradcrumbs(){
		
		
		$uri = $this->_ci->uri->segments;
		
		$html = '<ol class="breadcrumb">';
		
		foreach($uri as $item){
			
			if($item != 'index'){
                $tmp = explode('-', $item);
                $_crumb = '';
                
                foreach($tmp as $key){
                    $_crumb .= ucfirst($key).' ';
                }
                $html .= '<li>'.$_crumb.'</li>';
            }
		}
		
		
		$html .= '</ol>';
	
		return $html;
		
	}
	
	
	/**
	 * generate html for meta tags
	 * @return $html;
	 */
	protected function _load_meta_tag(){
		
		$html = '';
		
		foreach($this->_meta_tag as $meta){
			
			if(isset($meta['name']) && $meta['name'] != ''){
				if(isset($meta['comment']) && $meta['comment'] != '')
					$html .= '<!-- '.$meta['comment'].' -->'.PHP_EOL;
				$content = isset($meta['content']) && $meta['content'] != '' ? $meta['content'] : '';
				$html .= '<meta name="'.$meta['name'].'" content="'.$content.'" />'.PHP_EOL;
			}
			
		}
		return $html;
		
		
	}
    
    
	/**
	 * load theme skin
	 */
    protected function  _load_skin(){
        
        if(isset($_SESSION['user']['theme-skin']) && $_SESSION['user']['theme-skin'] != ''){
            return $_SESSION['user']['theme-skin'];    
        }else{
        
            $this->_ci->load->database();
            $this->_ci->load->model('configuration');
            $skin =  $this->_ci->configuration->get_config_value('theme_skin');
            $_SESSION['theme-skin'] = $skin;
            return $skin;
        }
    }
    
    
	
	/**
	 * load language
	 */
    protected function _load_language(){
        if(isset($_SESSION['language']) && $_SESSION['language'] != ''){
            return $_SESSION['language'];
        }else{
           
            $this->_ci->load->database();
            $this->_ci->load->model('configuration');
            $languages = json_decode($this->_ci->configuration->get_config_value('languages'),TRUE);
            $language = $this->_ci->configuration->get_config_value('language');
            $_SESSION['language'] = $languages[$language];
            return $languages[$language];
        } 
        
        
    }
	
	
	/**
	 * set if show wizard on first setup
	 */
	function set_setup_wizard($bool){	
		$this->_setup_wizard = $bool;
	}
	
	/**
	 * get if show wizard on first setup
	 */
	function get_setup_wizard(){
		return $this->_setup_wizard;
	}
	

	/**
	 * compress html
	 */
	protected function _do_compression($string){
		
		$buffer = $string;
		       
        $_search = array('/ {2,}/', '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s');
        $_replace = array(' ','');
        
        return trim(preg_replace($_search, $_replace, $buffer));
        
	}
	
	
	
	


}