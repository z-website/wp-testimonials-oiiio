<?php
/*
Plugin Name: wp testimonials oiiio
Plugin URI: https://oiiio.tech
Description: A jQuery testimonials for your wordpress website
Author: Mahamud Hasan Rashel
Version: 6.0
Author URI: http://rasel-portfolio.com
*/

// modernizr
add_action('wp_enqueue_scripts','modernizr_script');
function modernizr_script(){
	wp_enqueue_script('modernizr-script-main', plugins_url( '/js/modernizr.js' , __FILE__ ));
}

// wordpress default jquery file added
function oiiio_main_jquery() {
	wp_enqueue_script('jquery');
}
add_filter('wp_footer', 'oiiio_main_jquery');

// testimonial jquery file added
add_action('wp_enqueue_scripts','oiiio_testimonials_jquery');
function oiiio_testimonials_jquery(){
	wp_enqueue_script('testimonials-jquery-main', plugins_url( '/js/testimonials-jquery.min.js' , __FILE__ ), array('jquery'), '',true);
}

// testimonials stylesheet added
function testimonials_adding_styles() {
	wp_enqueue_style('my_stylesheet', plugins_url('css/testimonials-style.css', __FILE__) );
}

add_action( 'wp_enqueue_scripts', 'testimonials_adding_styles' );  

// admin color picker added
add_action( 'admin_enqueue_scripts', 'scrollbar_ppm_color_pickr_function' );
	function scrollbar_ppm_color_pickr_function( $hook_suffix ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'my-script-handle', plugins_url('js/color-pickr.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
// post type register
function create_post_type() {
	register_post_type( 'testimonial',
		array(
			'labels' => array(
				'name' => __( 'Testimonial' ),
				'singular_name' => __( 'Testimonial' ),
				'add_new' => __( 'Add New' ),
				'add_new_item' => __( 'Add New Testimonial' ),
				'edit_item' => __( 'Edit Testimonial' ),
				'new_item' => __( 'New Testimonial' ),
				'view_item' => __( 'View Testimonial' ),
				'not_found' => __( 'Sorry, we couldn\'t find the Testimonial you are looking for.' )
			),
		'public' => true,
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'menu_position' => 14,
		'has_archive' => false,
		'hierarchical' => false, 
		'capability_type' => 'page',
		'rewrite' => array( 'slug' => 'testimonial' ),
		'supports' => array( 'title')
		)
	);		
	
	
}
add_action( 'init', 'create_post_type' );
// custome field register
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'inc/init.php';

}
add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );

// Add Meta Boxes
function wpb_sample_metaboxes( $meta_boxes ) {
	$prefix = '_cmb_';

	$meta_boxes['test_metabox'] = array(
		'id'         => 'test_metabox',
		'title'      => __( 'Test Metabox', 'cmb' ),
		'pages'      => array( 'testimonial', ),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
			array(
				'name' => 'Testimonial description',
				'desc' => 'Write here your testimonial',
				'std' => '',
				'id' => $prefix . 'testimonial',
				'type' => 'textarea'
			),
			array(
			'name' => 'Name',
			'id' => $prefix . 'name',
			'type' => 'text'
			),			
			array(
				'name' => 'Upload Image',
				'desc' => 'you should use same size image, our recommended size is 150px height and 150px width',
				'id' => $prefix . 'image',
				'type' => 'file',
				'allow' => array( 'url', 'attachment' )
			),			
					
		)
	);
			return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'wpb_sample_metaboxes'  );

// testimonials loop
function testimonial_loop($atts){
ob_start(); 
?>
<div id="cbp-qtrotator" class="cbp-qtrotator">
<?php global $post; query_posts('post_type=testimonial&post_status=publish'); ?><?php if(have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
    <div class="cbp-qtcontent">
		<?php
			$image = get_post_meta( $post->ID, '_cmb_image', true );
        ?>
        <?php if($image) {?>
        <img src="<?php echo $image; ?>" />
        <?php } ?>
          <footer>
			<?php
				global $post;
				$name = get_post_meta( $post->ID, '_cmb_name', true );
				echo $name;
            ?>          
          </footer>
        	<p><?php
				global $post;
				$testimonialdes = get_post_meta( $post->ID, '_cmb_testimonial', true );
				echo $testimonialdes;
            ?></p>
    </div>
<?php endwhile; ?>	
<?php endif; ?>
<?php wp_reset_query();?>
</div>
<?php 
$content = ob_get_clean(); return $content;
}
// register short code
add_shortcode('testimonials', 'testimonial_loop');

// short code button
function buttons() {
	add_filter ("mce_external_plugins", "external_js");
	add_filter ("mce_buttons", "our_buttons");
}

function external_js($plugin_array) {
	$plugin_array['oiiio'] = plugins_url( '/js/custom-button.js' , __FILE__ );
	return $plugin_array;
}

function our_buttons($buttons) {
	array_push ($buttons, 'testimonials');
	return $buttons;
}
add_action ('init', 'buttons');

// Adding menu
function oiiio_testimonials_menu_options()  
{  
	add_submenu_page('edit.php?post_type=testimonial', 'Settings', 'Settings', 'manage_options', 'wp-testimonial-oiiio','oiiio_testimonials_options_page');  
} 

add_action('admin_menu', 'oiiio_testimonials_menu_options');
// Default options values
$oiiio_testimonials_options = array(
	'speed' => '10000',
	'process-bar' => '#47a3da',
	'font-color' => '#383838',
	'name-color' => '#47a3da'
);

if ( is_admin() ) : // Load only if we are viewing an admin page

function testimonials_register_settings() {
	register_setting( 'testimonials_p_options', 'oiiio_testimonials_options', 'testimonials_validate_options' );
}

add_action( 'admin_init', 'testimonials_register_settings' );

// options page functions
function oiiio_testimonials_options_page() {
	require('inc/options.php');
}

// form validation
function testimonials_validate_options( $input ) {
	global $oiiio_testimonials_options;

	$settings = get_option( 'oiiio_testimonials_options', $oiiio_testimonials_options );

	$input['speed'] = wp_filter_post_kses( $input['speed'] );
	$input['process-bar'] = wp_filter_post_kses( $input['process-bar'] );
	$input['font-color'] = wp_filter_post_kses( $input['font-color'] );
	$input['name-color'] = wp_filter_post_kses( $input['name-color'] );
	
	return $input;
}
endif;  // EndIf is_admin()
// active hook
function oiiio_testimonials_active_hook() {?>
<?php global $oiiio_testimonials_options; $testimonials_settings = get_option( 'oiiio_testimonials_options', $oiiio_testimonials_options ); ?>
	<script type="text/javascript">

		jQuery( function($) {
			jQuery( '.cbp-qtrotator' ).cbpQTRotator({
					speed :700,
					easing : 'ease',
					interval : <?php echo $testimonials_settings["speed"]; ?>,
				});
	
		} );

	</script>
    <style>
		.cbp-qtprogress {
			background: <?php echo $testimonials_settings['process-bar']; ?>;
		}
		.cbp-qtrotator p {
			color: <?php echo $testimonials_settings['font-color']; ?>;
		}	
		.cbp-qtrotator footer {
			color : <?php echo $testimonials_settings['name-color']; ?>;
		}			
	</style>
<?php } ?>
<?php add_action('wp_footer', 'oiiio_testimonials_active_hook'); ?>