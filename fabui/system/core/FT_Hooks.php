<?php



class FT_Hooks {


	public static $_filters = array();

	public static $_actions;

	public static $_merged_filters = array();

	public static $_current_filter;

	public static $_plugins_header = array ();

	public static $_themes_header = array ();

	public static $_current_theme;

	public static $_controller;

	
	function __construct()
	{
		
	
	}


	/**
	 * Registers a filtering function
	 *
	 * Typical use: hooks::add_filter('some_hook', 'function_handler_for_hook');
	 *
	 * @global array $filters Storage for all of the filters
	 * @param string $hook the name of the PM element to be filtered or PM action to be triggered
	 * @param callback $function the name of the function that is to be called.
	 * @param integer $priority optional. Used to specify the order in which the functions associated with a particular action are executed (default=10, lower=earlier execution, and functions with the same priority are executed in the order in which they were added to the filter)
	 * @param int $accepted_args optional. The number of arguments the function accept (default is the number provided).
	 */
	public static function add_filter( $hook, $function, $priority = 10, $accepted_args = NULL ) {

		// At this point, we cannot check if the function exists, as it may well be defined later (which is OK)
		$id = self::filter_unique_id( $hook, $function, $priority );

		
		self::$_filters[$hook][$priority][$id] = array(
				'function' => $function,
				'accepted_args' => $accepted_args,
		);


	}
	
	
	
	
	
	/**
	 * add_action
	 * Adds a hook
	 *
	 * @param string $hook
	 * @param string $function
	 * @param integer $priority (optional)
	 * @param integer $accepted_args (optional)
	 *
	 */
	public static function add_action($hook, $function, $priority = 10, $accepted_args = 1) {
	
		return self::add_filter( $hook, $function, $priority, $accepted_args );
	}
	
	
	
	/**
	 * Build Unique ID for storage and retrieval.
	 *
	 * Simply using a function name is not enough, as several functions can have the same name when they are enclosed in classes.
	 *
	 * @param string $hook
	 * @param string|array $function used for creating unique id
	 * @param int|bool $priority used in counting how many hooks were applied.  If === false and $function is an object reference, we return the unique id only if it already has one, false otherwise.
	 * @return string unique ID for usage as array key
	 */
	public static function filter_unique_id( $hook, $function, $priority ) {
	
		// If function then just skip all of the tests and not overwrite the following.
		if ( is_string($function) )
			return $function;
		// Object Class Calling
		else if (is_object($function[0]) ) {
			$obj_idx = get_class($function[0]).$function[1];
			if ( !isset($function[0]->_filters_id) ) {
				if ( false === $priority )
					return false;
				$count = isset($this->_filters[$hook][$priority]) ? count((array)$this->_filters[$hook][$priority]) : 0;
				$function[0]->_filters_id = $count;
				$obj_idx .= $count;
				unset($count);
			} else
				$obj_idx .= $function[0]->_filters_id;
			return $obj_idx;
		}
		// Static Calling
		else if ( is_string($function[0]) )
			return $function[0].$function[1];
	}
	
	
	
	/**
	 * Performs a filtering operation on a PM element or event.
	 *
	 * Typical use:
	 *
	 * 		1) Modify a variable if a function is attached to hook 'hook'
	 *		$var = "default value";
	 *		$var = hooks::apply_filter( 'hook', $var );
	 *
	 *		2) Trigger functions is attached to event 'pm_event'
	 *		hooks::apply_filter( 'event' );
	 *       (see hooks::do_action() )
	 *
	 * Returns an element which may have been filtered by a filter.
	 *
	 * @global array $filters storage for all of the filters
	 * @param string $hook the name of the the element or action
	 * @param mixed $value the value of the element before filtering
	 * @return mixed
	 */
	public static function apply_filter( $hook, $value = '' ) {
	
	
	
		if ( !isset( self::$_filters[$hook] ) )
			return $value;
	
	
		$args = func_get_args();
	
		// Sort filters by priority
		ksort( self::$_filters[$hook] );
	
		// Loops through each filter
		reset( self::$_filters[$hook] );
	
	
	
		do {
			foreach( (array) current(self::$_filters[$hook]) as $the_ )
	
	
	
	
				if ( !is_null($the_['function']) ){
				$args[1] = $value;
				$count = $the_['accepted_args'];
	
				if (is_null($count)) {
					$value = call_user_func_array($the_['function'], array_slice($args, 1));
				} else {
	
	
					$value = call_user_func_array(array(self::$_controller, $the_['function']), array_slice($args, 1, (int) $count));
				}
			}
	
		} while ( next(self::$_filters[$hook]) !== false );
	
		return $value;
	}
	
	
	
	public static function do_action( $hook, $arg = '' ) {
	
	
		$args = array();
		if ( is_array($arg) && 1 == count($arg) && isset($arg[0]) && is_object($arg[0]) ) // array(&$this)
			$args[] =& $arg[0];
		else
			$args[] = $arg;
		for ( $a = 2; $a < func_num_args(); $a++ )
			$args[] = func_get_arg($a);
	
	
		self::apply_filter( $hook, $args );
	}
	
	
	
	public function call_all_hook($args) {
	
		reset( $this->_filters['all'] );
		do {
			foreach( (array) current($this->_filters['all']) as $the_ )
				if ( !is_null($the_['function']) )
				call_user_func_array($the_['function'], $args);
	
		} while ( next($this->_filters['all']) !== false );
	}
	
	
	
	
	public function do_action_array($hook, $args) {
	
		if ( ! isset($this->_actions) )
			$this->_actions = array();
	
		if ( ! isset($this->_actions[$hook]) )
			$this->_actions[$hook] = 1;
		else
			++$this->_actions[$hook];
	
		// Do 'all' actions first
		if ( isset($this->_filters['all']) ) {
			$this->_current_filter[] = $hook;
			$all_args = func_get_args();
			$this->call_all_hook($all_args);
		}
	
		if ( !isset($this->_filters[$hook]) ) {
			if ( isset($this->_filters['all']) )
				array_pop($this->_current_filter);
			return;
		}
	
		if ( !isset($this->_filters['all']) )
			$this->_current_filter[] = $hook;
	
		// Sort
		if ( !isset( $this->_merged_filters[ $hook ] ) ) {
			ksort($this->_filters[$hook]);
			$this->_merged_filters[ $hook ] = true;
		}
	
		reset( $this->_filters[ $hook ] );
	
		do {
			foreach( (array) current($this->_filters[$hook]) as $the_ )
				if ( !is_null($the_['function']) )
				call_user_func_array($the_['function'], array_slice($args, 0, (int) $the_['accepted_args']));
	
		} while ( next($this->_filters[$hook]) !== false );
	
		array_pop($this->_current_filter);
	}
	
	
	
	/**
	 * Removes a function from a specified filter hook.
	 *
	 * This function removes a function attached to a specified filter hook. This
	 * method can be used to remove default functions attached to a specific filter
	 * hook and possibly replace them with a substitute.
	 *
	 * To remove a hook, the $function_to_remove and $priority arguments must match
	 * when the hook was added.
	 *
	 * @global array $filters storage for all of the filters
	 * @param string $hook The filter hook to which the function to be removed is hooked.
	 * @param callback $function_to_remove The name of the function which should be removed.
	 * @param int $priority optional. The priority of the function (default: 10).
	 * @param int $accepted_args optional. The number of arguments the function accepts (default: 1).
	 * @return boolean Whether the function was registered as a filter before it was removed.
	 */
	public function remove_filter( $hook, $function_to_remove, $priority = 10, $accepted_args = 1 ) {
	
		$function_to_remove = $this->filter_unique_id($hook, $function_to_remove, $priority);
	
		$remove = isset ($this->_filters[$hook][$priority][$function_to_remove]);
	
		if ( $remove === true ) {
			unset ($this->_filters[$hook][$priority][$function_to_remove]);
			if ( empty($this->_filters[$hook][$priority]) )
				unset ($this->_filters[$hook]);
		}
		return $remove;
	}
	
	
	
	
	/**
	 * Check if any filter has been registered for a hook.
	 *
	 * @global array $filters storage for all of the filters
	 * @param string $hook The name of the filter hook.
	 * @param callback $function_to_check optional.  If specified, return the priority of that function on this hook or false if not attached.
	 * @return int|boolean Optionally returns the priority on that hook for the specified function.
	 */
	public function has_filter( $hook, $function_to_check = false ) {
	
		$has = !empty($this->_filters[$hook]);
		if ( false === $function_to_check || false == $has ) {
			return $has;
		}
		if ( !$idx = $this->filter_unique_id($hook, $function_to_check, false) ) {
			return false;
		}
	
		foreach ( (array) array_keys($this->_filters[$hook]) as $priority ) {
			if ( isset($this->_filters[$hook][$priority][$idx]) )
				return $priority;
		}
		return false;
	}
	
	
	
	public function has_action( $hook, $function_to_check = false ) {
		return $this->has_filter( $hook, $function_to_check );
	}
	
	
	public static function set_controller($controller){
		self::$_controller = $controller;
	}
	
	
	
	
	



}