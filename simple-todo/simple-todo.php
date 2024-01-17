<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Plugin Name:Simple Todo
 * Description: This is a simple todo plugin.
 * Version: 1.0.0
 * Author: Nahidul Islam Sayel
 * Author URI: http://www.cleancodewithsumon.com
 */

define( 'SIMPLE_TODO_VERSION', '1.0.0' );
define( 'SIMPLE_TODO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SIMPLE_TODO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SIMPLE_TODO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SIMPLE_TODO_PLUGIN_FILE', __FILE__ );

require_once SIMPLE_TODO_PLUGIN_DIR . 'src/simple_todo_functions.php';

register_activation_hook( __FILE__, 'simple_todo_activate' );


add_action( 'admin_menu', 'simple_todo_admin_menu' );
add_action( 'admin_enqueue_scripts', 'simple_todo_enqueue_scripts' );
