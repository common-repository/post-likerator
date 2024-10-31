<?php

/**
 * Manage Actions and Filters
 */

if (!class_exists('Flip_Hooks')){

	class Flip_Hooks {
		protected $actions;
		protected $filters;
		protected $debug_tags;

		public function __construct(){
			$this->actions = array();
			$this->filters = array();
			$this->debug_tags = array();
		}

		/**
		 * Build a standard array containing the parameters for an action or a filter
		 *
		 * @param string $type 'action' or 'filter'
		 * @param string $hook_name Name of the WordPress hook
		 * @param object $class_object The object containing the public function
		 * @param string $callback_name The name of the public function in the object to be used as a callback when the hook runs
		 * @param int $priority Used to specify the order in which the functions associated with a particular hook are executed
		 * @param int $accepted_args The number of arguments the callback function accepts
		 */
		private function add($type, $hook_name, $class_object, $callback_name, $priority = 10, $accepted_args = 1){
			$new = array(
				'hook_name'=>$hook_name,
				'class_object'=>$class_object,
				'callback_name'=>$callback_name,
				'priority'=>$priority,
				'accepted_args'=>$accepted_args
				);

			switch ($type){
				case 'filter':
				$this->filters[] = $new;
				break;

				case 'action':
				default:
				$this->actions[] = $new;
				break;
			}
		}

		/**
		 * Wrapper for the add() function used when adding actions
		 *
		 * @param string $hook_name Name of the WordPress action
		 * @param object $class_object The object containing the public function
		 * @param string $callback_name The name of the public function in the object to be used as a callback when the action runs
		 * @param int $priority Used to specify the order in which the functions associated with a particular action are executed
		 * @param int $accepted_args The number of arguments the callback function accepts
		 */
		public function add_action($hook_name, $class_object, $callback_name, $priority = 10, $accepted_args = 1){
			$this->add('action', $hook_name, $class_object, $callback_name, $priority, $accepted_args);
		}

		/**
		 * Wrapper for the add() function used when adding filters
		 *
		 * @param string $hook_name Name of the WordPress filter
		 * @param object $class_object The object containing the public function
		 * @param string $callback_name The name of the public function in the object to be used as a callback when the filter runs
		 * @param int $priority Used to specify the order in which the functions associated with a particular filter are executed
		 * @param int $accepted_args The number of arguments the callback function accepts
		 */
		public function add_filter($hook_name, $class_object, $callback_name, $priority = 10, $accepted_args = 1){
			$this->add('filter', $hook_name, $class_object, $callback_name, $priority, $accepted_args);
		}

		/**
		 * Register all actions and filters with WordPress
		 */
		public function register(){
			foreach ($this->actions as $action) {
				add_action( $action['hook_name'], array($action['class_object'], $action['callback_name']), $action['priority'], $action['accepted_args'] );
			}

			foreach ($this->filters as $filter) {
				add_filter( $filter['hook_name'], array($filter['class_object'], $filter['callback_name']), $filter['priority'], $filter['accepted_args'] );
			}
		}

		/**
		 * Get a combined array of actions and filters
		 *
		 * @return array The array of actions and filters
		 */
		public function get(){
			return array(
				'actions'=>$this->actions,
				'filters'=>$this->filters
				);
		}

		/**
		 * Remove an action from the internal array and from WordPress
		 *
		 * @param int $key The key in the $this->actions array that holds the action to be removed
		 * @return boolean Whether the action was successfully removed
		 */
		public function remove_action($key){
			$action = $this->actions[$key];
			$success = remove_action( $action['hook_name'], array($action['class_object'], $action['callback_name']), $action['priority'] );
			unset($this->actions[$key]);
			return $success;
		}

		/**
		 * Remove filter from the internal array and from WordPress
		 *
		 * @param int $key The key in the $this->filters array that holds the filter to be removed
		 * @return boolean Whether the filter was successfully removed
		 */
		public function remove_filter($key){
			$filter = $this->filters[$key];
			$success = remove_filter( $filter['hook_name'], array($filter['class_object'], $filter['callback_name']), $filter['priority'] );
			unset($this->filters[$key]);
			return $success;
		}

		public function debug_live(){
			$this->add_action('all', $this, 'debug_output_tag');
		}

		public function debug_output_tag($tag){
			print_r('<pre>'.$tag.'</pre>');
		}

		public function debug_list(){
			$this->add_action('all', $this, 'debug_collect_tags');
			$this->add_action('shutdown', $this, 'debug_output_tags');
		}

		public function debug_collect_tags($tag){
			if ( in_array( $tag, $this->debug_tags ) ) {
		        return;
		    }
		    $this->debug_tags[] = $tag;
		}

		public function debug_output_tags(){
			foreach ($this->debug_tags as $tag) {
				print_r('<pre>'.$tag.'</pre>'."\n");
			}
		}
	}
	
}