<?php

/*-----Add CPT freelancers------*/
add_action( 'init', 'register_post_type_freelancer' ); 
function register_post_type_freelancer() {

	$labels = array(
		'name' => 'Freelancers',
		'singular_name' => 'Freelancers',
		'add_new' => 'Add Freelancer',
		'add_new_item' => 'Add New Freelancer',
		'edit_item' => 'Edit Freelancer',
		'new_item' => 'New Freelancer',
		'all_items' => 'All Freelancers',
		'view_item' => 'View Freelancer on site',
		'search_items' => 'Search Freelancers',
		'not_found' =>  'Freelancers not found.',
		'not_found_in_trash' => 'No Freelancers in the cart.',
		'menu_name' => 'Freelancers'
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true,
		'has_archive' => true, 
		'menu_icon' => 'dashicons-groups',
		'menu_position' => 30,
		'supports' => array('title')
	);
	register_post_type('freelancer', $args);
}

/*------Add avatar block media-----------*/
function avatar_field( $name, $value = '', $w = 100, $h = 100) {
	$nophoto = plugins_url('img/no-image.png', __file__);
	if( $value ) {
		$image_attributes = wp_get_attachment_image_src( $value, array($w, $h) );
		$src = $image_attributes[0];
	} else {
		$src = $nophoto;
	}
	echo '
	<div class="avatar_fr">
		<img data-src="' . $nophoto . '" src="' . $src . '" width="' . $w . 'px" height="' . $h . 'px" />
		<div>
			<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
			<button type="submit" class="upload_image_button button">Load</button>
			<button type="submit" class="remove_image_button button">&times;</button>
		</div>
	</div>
	';
}

/*---Add avatar metabox----*/
function avatar_meta_box() {
	add_meta_box('truediv', 'Avatar', 'avatar_box', 'freelancer', 'normal', 'high');
}
add_action( 'admin_menu', 'avatar_meta_box' );

/*-----Upload image show-----*/
function avatar_box($post) {
	if( function_exists( 'avatar_field' ) ) {
		avatar_field( 'uploader_avatar', get_post_meta($post->ID, 'uploader_avatar',true) );
	}
}

/*------Save avatar-------*/
function avatar_box_save( $post_id ) {
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;
	update_post_meta( $post_id, 'uploader_avatar', $_POST['uploader_avatar']);
	return $post_id;
}
add_action('save_post', 'avatar_box_save');

/*----------Add meta box Name-------------*/
function meta_name() {
	add_meta_box( 'meta_box', 'Name', 'meta_func', 'freelancer', 'normal', 'high' );
}
add_action('admin_init', 'meta_name', 1);

/*----------Add Name block-------------*/
function meta_func( $post ){
	?> 
	 <div>  
	  <div>  
	   <input type="text" placeholder="Name" name="extra[name]" value="<?php echo get_post_meta($post->ID, "name", true); ?>" style="width: 50%">  
	  </div>  
	 </div> 
	 <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" /> 

	<?php
}
add_action('save_post', 'meta_save', 0);

/*----------Save Name block-------------*/
function meta_save( $post_id ){
	if ( !wp_verify_nonce($_POST['extra_fields_nonce'], __FILE__) ) return false;
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false;
	if ( !current_user_can('edit_post', $post_id) ) return false;
	if( !isset($_POST['extra']) ) return false;
	$_POST['extra'] = array_map('trim', $_POST['extra']);
	foreach( $_POST['extra'] as $key=>$value ){
		if( empty($value) ) {
			delete_post_meta($post_id, $key);
			continue;
		}
		update_post_meta($post_id, $key, $value);
	}
	return $post_id;
}

/*----Add meta box select freelancer in task----*/
function true_meta_boxes() {
	add_meta_box('truediv', 'Freelancer', 'freelancer_box', 'task', 'side', 'default');
}
add_action( 'admin_menu', 'true_meta_boxes' );

/*----Add block select freelancer in task----*/
function freelancer_box($post) {
	wp_nonce_field( basename( __FILE__ ), 'freelancer_metabox_nonce' );
	global $post;
	$args = array(
		'posts_per_page' => -1,
		'post_type' => 'freelancer'
	);
	$all_freelancer = get_posts( $args );
	$current_post = get_the_id();
	?>
	<select name="freelancer_select">
		<option value="Not assigned">Not assigned</option>
		<?php 
		foreach( $all_freelancer as $freelancer ) { 
		setup_postdata($freelancer); ?>
		<?php
		$name = get_post_meta($freelancer->ID, 'name', true);
		$current_name = get_post_meta($current_post, 'freelancer_select', true);
		?>
		<option <?php if($name == $current_name) { echo 'selected'; } ?> value="<?php echo $name; ?>"><?php echo $name; ?></option>
		<?php } ?>
	</select>
	<?php 
	wp_reset_postdata(); 
	}

/*-------Save Select freelancer in task -------*/
function save_box_freelancer ( $post_id ) {
	if ( !isset( $_POST['freelancer_metabox_nonce'] )
	|| !wp_verify_nonce( $_POST['freelancer_metabox_nonce'], basename( __FILE__ ) ) ) return $post_id;
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
	if ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;
	$post = get_post($post_id);
	if ($post->post_type == 'task') {
		update_post_meta($post_id, 'freelancer_select', esc_attr($_POST['freelancer_select']));
	}
	return $post_id;
}
add_action('save_post', 'save_box_freelancer');



class CodingPlus {
	
	public function __construct() {		
		add_filter('pre_get_document_title', [$this, 'add_dashboard_page_title'], 10, 1);
		add_filter('cn_tasks_thead_cols', [$this, 'list_task_thead_cols'], 10, 1);
		add_filter('cn_tasks_tbody_row_cols', [$this, 'list_task_tbody_cols'], 10, 2);
		add_action('wp_enqueue_scripts', [$this, 'resource_tables'], 100);
		add_filter('cn_menu', [$this, 'add_menu_link'], 10, 1);
		add_action('cn_after_content', [$this, 'modal_content']);
		add_action('wp_ajax_add_task_from_form', [$this, 'add_task_from_form']);
        add_action('wp_ajax_nopriv_add_task_from_form', [$this, 'add_task_from_form']);
		add_shortcode('cn_dashboard', [$this, 'add_shortcode']);
	}

	/*-------Add title pages--------*/
	function add_dashboard_page_title($title) {
        $url = parse_url($_SERVER['REQUEST_URI']);
        switch ($url['path']) {
            case '/dashboard': $title = 'Dashboard'; break;
            case '/tasks': $title = 'Tasks'; break;
        }
        return $title;
    }
	
	/*------Add thead table name-------*/
	function list_task_thead_cols($cols) {
        $cols[] = 'Freelancer';
        return $cols;
    }
	
	/*-----Add tbody table freelancers-----*/
	function list_task_tbody_cols($cols, $task) {
		$task_id = str_replace('#', '', $task->id());
        $col = get_post_meta($task_id, 'freelancer_select', true);
		if ($col) {
			$cols[] = get_post_meta($task_id, 'freelancer_select', true);
		} else {
			$cols[] = 'Not assigned';
		}
        return $cols;
    }
	
	/*-----Add resource table search, show, sort, and other-----*/
	function resource_tables() {
		wp_enqueue_style('tables-css',  plugins_url('css/datatables.min.css', __file__));
        wp_enqueue_script('tables-script', plugins_url('js/datatables.min.js', __file__), ['jquery']);
		wp_enqueue_script( 'main', plugins_url('js/main.js', __file__));
	}
	
	/*------Add side menu--*/
	function add_menu_link($menu_link) {
        $menu_link['javascript:;'] = 
		[
			'title' => 'Add New Task',
			'icon' => 'fa-plus-circle'
		]; 
        return $menu_link;
    }
	
	/*-----Add modal wimdow------*/
	function modal_content() {
		require_once plugin_dir_path(__file__) . 'modal.php';
	}
	
	/*-------Add new task------*/
 	function add_task_from_form()
    {
        $json = ['success' => false];
        $post =
        [
            'post_title'  => $_POST['form_title'],
            'post_type'   => 'task',
            'post_status' => 'publish',
			'post_author'   => 1,
			'meta_key'   => 'freelancer_select',
			'meta_value'   => $_POST['form_freelancer'],
        ];
        $post_id = wp_insert_post($post, true);
        if (!is_wp_error($post_id))
        {
			update_post_meta($post_id, 'freelancer_select', $_POST['form_freelancer']);
            $json['success'] = true;
        }
        die(json_encode($json));
    }
	
	/*---Add shortkodes------*/
	function add_shortcode($atts, $content) {
        ob_start();
        require_once plugin_dir_path(__file__). '/shortcode.php';
        return ob_get_clean();
    }
	
}
new CodingPlus();
?>