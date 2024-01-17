<?php

function simple_todo_init() {
	var_dump( 'init_simple_todo' );

}

function simple_todo_activate() {
	global $wpdb;
	$table_name      = $wpdb->prefix . 'simple_todo';
	$charset_collate = $wpdb->get_charset_collate();
	$sql             = 'CREATE TABLE IF NOT EXISTS ' . $table_name . ' (
		id bigint NOT NULL AUTO_INCREMENT,
		title varchar(255) NOT NULL,
		description text DEFAULT NULL,
		created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		PRIMARY KEY (id)
	) ' . $charset_collate . ';';
	if ( ! function_exists( 'dbDelta' ) ) {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	}

	dbDelta( $sql );

}

function simple_todo_admin_menu() {
	add_menu_page(
		__( 'Simple Todo', 'simple-todo' ),
		__( 'Simple Todo', 'simple-todo' ),
		'manage_options',
		'simple-todo',
		'simple_todo_admin_page',
		'dashicons-list-view',
		20
	);
}
function simple_todo_admin_page() {
		// For insert and update
		if (isset($_POST['simple-todo-nonce']) && wp_verify_nonce($_POST['simple-todo-nonce'], 'simple-todo-nonce-action')) {
			if (isset($_POST['id']) && $_POST['id'] != '') {
				$id = intval($_POST['id']);
				simple_todo_update_todo($id);
			} else {
				simple_todo_insert_todo();
			}
		}
	// for delete
	if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
		$id = intval($_GET['id']);
		simple_todo_detete_todo($id);
	}
	//for edit
	if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
		$id = intval($_GET['id']);
		$todo = simple_todo_get_todo_by_id($id);
	}

	$todos = simple_todo_get_todos();
	require_once SIMPLE_TODO_PLUGIN_DIR . 'src/simple_todo_table.php';
	
}
function simple_todo_enqueue_scripts() {
	wp_enqueue_style('simple-todo-bootstrap-min', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', array(), '4.5.2', 'all');
	wp_enqueue_style( 'simple-todo-style', SIMPLE_TODO_PLUGIN_URL . 'assets/css/style.css', array(), SIMPLE_TODO_VERSION, 'all' );
}

function simple_todo_get_todos()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'simple_todo';
	$sql = 'SELECT * FROM ' . $table_name . ' ORDER BY id ASC';
	$items = $wpdb->get_results( $sql);
	return $items;
}

function simple_todo_detete_todo($id)
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'simple_todo';
	$wpdb->delete( $table_name, array( 'id' => $id ) );
}

function simple_todo_insert_todo()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'simple_todo';

	$title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
	$description =  isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : '';

	$wpdb->insert(
		$table_name,
		array(
			'title' => $title,
			'description' => $description,
		)
	);
}
function simple_todo_get_todo_by_id($id)
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'simple_todo';
	$sql = 'SELECT * FROM ' . $table_name . ' WHERE id = ' . $id;
	$item = $wpdb->get_row( $sql);

	return $item;
}

function simple_todo_update_todo($id)
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'simple_todo';

	$title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
	$description =  isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : '';

	$wpdb->update(
		$table_name,
		array(
			'title' => $title,
			'description' => $description,
		),
		array( 'id' => $id )
	);
}

function simple_todo_shortcode()
{
	add_shortcode( 'simple_todo', 'simple_todo_shortcode_callback' );
}
function simple_todo_shortcode_callback($atts = [], $content = null)
{
	// override default attributes with user attributes
	$shortcode_atts = shortcode_atts(
		array(
			'id' => '',
		), $atts);

	$id = intval($shortcode_atts['id']);

	global $wpdb;
	$table_name = $wpdb->prefix . 'simple_todo';

	$sql = 'SELECT * FROM ' . $table_name . ' WHERE id = ' . $id;
	$item = $wpdb->get_row( $sql);
	
	wp_enqueue_style( 'simple-todo-style', SIMPLE_TODO_PLUGIN_URL . 'assets/css/style.css', array(), SIMPLE_TODO_VERSION, 'all' );

	return simple_todo_load_view('simple-todo-shortcode', ['item' => $item]);
}

simple_todo_shortcode();
function simple_todo_load_view($view, $data = [])
{
	extract($data);
	ob_start();
	include SIMPLE_TODO_PLUGIN_DIR . 'src/' . $view . '.php';
	return ob_get_clean();
}