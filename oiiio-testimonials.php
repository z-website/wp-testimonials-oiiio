<?php
/**
 * Plugin Name:       wp testimonials oiiio
 * Plugin URI:        https://testimonails.rasel-portfolio.com/
 * Description:       A simple eloquent testimonials for wordpress website
 * Version:           6.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.0.0
 * Author:            Rasel Mahmud
 * Author URI:        https://rasel-portfolio.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       oiiio-testimonails
 * Domain Path:       /languages
 */

define("OT_ADMIN_ASSETS", plugin_dir_url(__FILE__). "assets/admin");
define("OT_PUBLIC_ASSETS", plugin_dir_url(__FILE__). "assets/public");
define("VERSION", '6.0.0');

use Carbon_Fields\Field;
use Carbon_Fields\Block;

class OiiiOTestimonials{
  public function __construct(){
    add_action('plugin_loaded', [$this, 'oiiio_testimonials_text_domain_load']);
    add_action('plugin_loaded', [$this, 'carbon_load']);
    add_action('wp_enqueue_scripts', [$this, 'front_end_assets']);
    add_action('admin_enqueue_scripts', [$this, 'backend_end_assets']);
    add_action('plugin_loaded', [$this, 'oiiio_testimonials_block']);
    add_action("admin_init", [$this, 'oiiio_testimonials_admin_option']);
  }
  
  // Text Domain Load
  public function oiiio_testimonials_text_domain_load(){
    load_plugin_textdomain('oiiio-testimonails', false, dirname(__FILE__). '/languages');
  }
  
  // Carbon Framework Load
  public function carbon_load(){
    require_once 'vendor/autoload.php';
    \Carbon_Fields\Carbon_Fields::boot();
  }
  
  // Front End Style And Script Load
  public function front_end_assets(){
    wp_enqueue_script('oiiio-testimonials-modernizr', OT_PUBLIC_ASSETS. '/js/modernizr.custom.js', VERSION, true);
    wp_enqueue_script('oiiio-testimonials', OT_PUBLIC_ASSETS. '/js/oiiio-testimonials.js', array('jquery'), VERSION, true);
    wp_enqueue_script('oiiio-testimonials-main', OT_PUBLIC_ASSETS. '/js/main.js', array('jquery'), VERSION, true);
    wp_enqueue_style('oiiio-testimonials', OT_PUBLIC_ASSETS. '/css/oiiio-testimonials.css', VERSION, VERSION);
    $slider_color = get_option('oiiio_testimonial_color');
    $name_font_size = get_option('oiiio_testimonial_name_font_size');
    $description_font_size = get_option('oiiio_testimonial_font_size');
    $font_color = get_option('oiiio_testimonial_font_color');
    $custom_css = "
                .cbp-qtprogress {
                  background: #{$slider_color};
                }
                .cbp-qtrotator p.oiiio-name {
                  font-size: {$name_font_size}px;
                }
                .cbp-qtrotator p.oiiio-description {
                  font-size: {$description_font_size}px;
                }
                .cbp-qtrotator p.oiiio-name, .cbp-qtrotator p.oiiio-description {
                  color: #{$font_color};
                }";
    wp_add_inline_style( 'oiiio-testimonials', $custom_css );
        
    wp_localize_script('oiiio-testimonials-main', 'oiiio_settings', [
      'ajax_url' => admin_url('ajax-ajax.php'),
      'speed'    =>  get_option('oiiio_testimonial_speed')
    ]);
  }
  
  // Back-end Style And Script
  public function backend_end_assets($screen){
    if( 'options-general.php' == $screen ){
      wp_enqueue_script('oiiio-testimonials-color', OT_ADMIN_ASSETS. '/js/jscolor.js', array('jquery'), VERSION, true);
    }
  }
  
  // Load Testimonial Block
  public function oiiio_testimonials_block(){
    Block::make(__('oiiio Testimonials'))
    ->set_description(__('A Simple eloquent testimonials for your wordpress website'))
    ->set_category('layout')
    ->set_icon('businessman')
    ->set_keywords( [__('oiiio'), __('testimonial')] )
    ->set_preview_mode(false)
    ->add_fields([
      Field::make( 'complex', 'oiiio_testimonials', __( 'Testimonials' ) )
      ->add_fields([
        Field::make( 'text', 'oiiio_name', __( 'Name' ) ),
        Field::make( 'textarea', 'oiiio_testimonials', __( 'Testimonial' ) ),
        Field::make( 'image', 'oiiio_image', __( 'Image' ) ),
        ])
        ])->set_render_callback( function( $fields, $attributes, $inner_blocks ) {
          ?>
      <div id="cbp-qtrotator" class="cbp-qtrotator">
          <?php
            foreach($fields[oiiio_testimonials] as $testimonial) : 
              ?>
          <div class="cbp-qtcontent">
            <?php echo wp_get_attachment_image($testimonial['oiiio_image']); ?>
            <p class="oiiio-name"><?php echo esc_html($testimonial['oiiio_name']); ?></p>
            <p class="oiiio-description"><?php echo esc_html($testimonial['oiiio_testimonials']); ?></p>
          </div>
          <?php 
            endforeach;
            ?>
      </div>
    <?php
    });
    
  }
  
  // Display Admin option Settings
  public function oiiio_testimonials_admin_option(){
    add_settings_section('testimonial_settings', __('Testimonials Plugin Settings :', 'oiiio-testimonails'), [$this, 'testimonial_settings_display'], 'general');
    add_settings_field('oiiio_testimonial_speed', __('Slide Speed', 'oiiio-testimonails'), [$this, 'testimonial_speed_settings'], 'general', 'testimonial_settings', array('oiiio_testimonial_speed'));
    add_settings_field('oiiio_testimonial_color', __('Color', 'oiiio-testimonails'), [$this, 'testimonial_color_settings'], 'general', 'testimonial_settings', array('oiiio_testimonial_color'));
    add_settings_field('oiiio_testimonial_name_font_size', __('Name Font Size', 'oiiio-testimonails'), [$this, 'testimonial_name_font_size_settings'], 'general', 'testimonial_settings', array('oiiio_testimonial_name_font_size'));
    add_settings_field('oiiio_testimonial_font_size', __('Testimonial Font Size', 'oiiio-testimonails'), [$this, 'testimonial_font_size_settings'], 'general', 'testimonial_settings', array('oiiio_testimonial_font_size'));
    add_settings_field('oiiio_testimonial_font_color', __('Testimonial Font Size', 'oiiio-testimonails'), [$this, 'testimonial_font_color_settings'], 'general', 'testimonial_settings', array('oiiio_testimonial_font_color'));
    
    register_setting("general", "oiiio_testimonial_speed", array('sanitize_callback' => 'esc_attr'));
    register_setting("general", "oiiio_testimonial_color", array('sanitize_callback' => 'esc_attr'));
    register_setting("general", "oiiio_testimonial_name_font_size", array('sanitize_callback' => 'esc_attr'));
    register_setting("general", "oiiio_testimonial_font_size", array('sanitize_callback' => 'esc_attr'));
    register_setting("general", "oiiio_testimonial_font_color", array('sanitize_callback' => 'esc_attr'));
  }

  // Slider Speed
  public function testimonial_speed_settings($args){
    $options = !empty(get_option($args[0])) ? get_option($args[0]) : 8000;
    printf("<input type='text' id='%s' name='%s' value='%s' />", $args[0], $args[0], $options );
    echo "<p>Default: 8000 (ms) </p>";
  }

  // Slider Color
  public function testimonial_color_settings($args){
    $options = !empty(get_option($args[0])) ? get_option($args[0]) : '#47a3da';
    printf("<input type='text' name='%s' class='jscolor' value='%s' />", $args[0], $options );
    echo "<p>Default: #47a3da (HEX) </p>";
  }

  // Name Font Size
  public function testimonial_name_font_size_settings($args){
    $options = !empty(get_option($args[0])) ? get_option($args[0]) : 14;
    printf("<input type='number' name='%s' value='%s' />", $args[0], $options );
    echo "<p>Default: 14 (px) </p>";
  }

  // Testimonial Font Size
  public function testimonial_font_size_settings($args){
    $options = !empty(get_option($args[0])) ? get_option($args[0]) : 18;
    printf("<input type='number' name='%s' value='%s' />", $args[0], $options );
    echo "<p>Default: 18 (px) </p>";
  }

  // Font Color
  public function testimonial_font_color_settings($args){
    $options = !empty(get_option($args[0])) ? get_option($args[0]) : '#888888';
    printf("<input type='text' name='%s' class='jscolor' value='%s' />", $args[0], $options );
    echo "<p>Default: #888888 (HEX) </p>";
  }

}
new OiiiOTestimonials();