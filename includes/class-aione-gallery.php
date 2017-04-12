<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://sgssandhu.com/
 * @since      1.0.0
 *
 * @package    Aione_Gallery
 * @subpackage Aione_Gallery/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Aione_Gallery
 * @subpackage Aione_Gallery/includes
 * @author     SGS Sandhu <sgs.sandhu@gmail.com>
 */
class Aione_Gallery {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Aione_Gallery_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'aione-gallery';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		
		//add_action( 'init', array($this,'remove_gallery_shortcode' ));
		add_shortcode( 'gallery', array($this, 'aione_gallery_shortcode') );

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Aione_Gallery_Loader. Orchestrates the hooks of the plugin.
	 * - Aione_Gallery_i18n. Defines internationalization functionality.
	 * - Aione_Gallery_Admin. Defines all hooks for the admin area.
	 * - Aione_Gallery_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-aione-gallery-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-aione-gallery-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-aione-gallery-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-aione-gallery-public.php';

		$this->loader = new Aione_Gallery_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Aione_Gallery_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Aione_Gallery_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Aione_Gallery_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Aione_Gallery_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Aione_Gallery_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
	public function remove_gallery_shortcode() {
		remove_shortcode( 'gallery' );
	}
	
	public function aione_gallery_shortcode( $attr ) {
	
		$html5 = current_theme_supports( 'html5', 'gallery' );
		$atts = shortcode_atts( array(
		//'order'       => 'ASC',
		//'orderby'     => 'menu_order ID',
		//'id'          => $post ? $post->ID : 0,
		'ids'         =>	'',
		'itemtag'     => $html5 ? 'figure'     : 'dl',
		'icontag'     => $html5 ? 'div'        : 'dt',
		'captiontag'  => $html5 ? 'figcaption' : 'dd',
		'columns'     =>  4,
		'size'        => 'medium',
		//'include'     => '',
		//'exclude'     => '',
		'link'        => '',
		'type'        => 'wall',
		'width'       => '100%',
		'height'      => '250px',
		'margin'      => 'yes',
		'padding'     => '15%',
		'outline'     => 'yes',
		'style'       => 'square',
		'animation'   => 'zoom',
		'transition'  => 'fade',
		'direction'   => 'bottom'
		), $attr, 'gallery' );

		$errors = array();
		$attachments = explode(',',$atts['ids']);
		
		if(trim($atts['ids']) == ''){
			$errors[] = "ids are empty";
		}
		
		
		$type = sanitize_html_class( $atts['type'] );
		$width = $atts['width'];
		$height = $atts['height'];
		$margin = $atts['margin'];
		$padding = $atts['padding'];
		if($margin == 'yes'){ 
			$margin_class = 'margin'; $margin = '0.75%';
		}
		if($margin == 'no'){ 
			$margin_class = 'nomargin'; $margin = '0';
		}
		$outline = sanitize_html_class( $atts['outline'] );
		if($outline == 'yes'){ 
			$outline = 'outline';
		} else {
			$outline = '';
		}
		$style = sanitize_html_class( $atts['style'] );
		$animation = sanitize_html_class( $atts['animation'] );
		$transition = sanitize_html_class( $atts['transition'] );
		$direction = sanitize_html_class( $atts['direction'] );
		$itemtag = tag_escape( $atts['itemtag'] );
		$captiontag = tag_escape( $atts['captiontag'] );
		$icontag = tag_escape( $atts['icontag'] );
		$valid_tags = wp_kses_allowed_html( 'post' );
		if ( ! isset( $valid_tags[ $itemtag ] ) ) {
			$itemtag = 'dl';
		}
		if ( ! isset( $valid_tags[ $captiontag ] ) ) {
			$captiontag = 'dd';
		}
		if ( ! isset( $valid_tags[ $icontag ] ) ) {
			$icontag = 'dt';
		}
		$columns = intval( $atts['columns'] );
		$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
		$itemheight = $columns > 0 ? floor(100/$columns) : 100;
		$float = is_rtl() ? 'right' : 'left';
		$selector = "gallery-{$instance}";
		$gallery_style = '';
		if ( apply_filters( 'use_default_gallery_style', ! $html5 ) ) {
			
			$gallery_style = "
			<style type='text/css'>
			#{$selector} {
			margin: auto;
			}
			#{$selector} .gallery-item {
			float: {$float};
			margin-top: 10px;
			text-align: center;
			width: {$itemwidth}%;
			}
			#{$selector} img {
			border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
			margin-left: 0;
			}
			/* see gallery_shortcode() in wp-includes/media.php */
			</style>\n\t\t";

		} else{
			
			$gallery_style = "
			<style type='text/css'>

			#{$selector} .gallery-item {
			position: relative;
			float: {$float};
			text-align: center;

			}
			#{$selector} .gallery-item:after {
			content: '';
			display: block;
			padding-bottom: 100%;
			}

			#{$selector} .gallery-item .gallery-icon{ 
			position: absolute;
			height: 100%;
			width: 100%;
			}
			#{$selector} .gallery-item .gallery-caption{
			padding-top: {$padding};
			position: absolute;
			height: 100%;
			width: 100%;
			min-height: 100%;
			min-width: 100%;
			}
			</style>\n\t\t";
		}

		$size_class = sanitize_html_class( $atts['size'] );
		$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} {$style} {$margin_class} {$outline} animation-{$animation} transition-{$transition}-{$direction}'>";
		$output = apply_filters( 'gallery_style', $gallery_style . $gallery_div );
		$i = 0;
		foreach ( $attachments as $key => $id ) {
			$i++;
			$attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';
			if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
				$image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false, $attr );
			} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
				$image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );
			} else {
				$image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr );
			}
			$image_meta  = wp_get_attachment_metadata( $id );
			$orientation = '';
			if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
				if ( $image_meta['height'] == $image_meta['width'] ) {
					$orientation = 'square';
				} elseif( $image_meta['height'] > $image_meta['width'] ){
					$orientation = 'portrait';
				} else {
					$orientation = 'landscape';
				}
			}
			$itemtag_class = '';
			if ( $columns > 0 && $i % $columns == 0 ) {
				$itemtag_class = 'last-item';
			}
			$output .= "<{$itemtag} id='gallery_item_{$i}' class='gallery-item {$itemtag_class}'>";
			$output .= "
			<{$icontag} class='gallery-icon {$orientation}'>
			$image_output
			</{$icontag}>";	
			if ( $captiontag ) {
				$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				<span class='title-m'>" . wptexturize($attachment->post_title) . "</span>
				<span class='excerpt-m'>" . wptexturize($attachment->post_excerpt) . "</span>
				</{$captiontag}>";
			}	
			$output .= "</{$itemtag}>";
			if ( ! $html5 && $columns > 0 && $i % $columns == 0 ) {
				$output .= '<br style="clear: both" />';
			}
		}
		if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
			$output .= "<br style='clear: both' />";
		} else {
			$output .= "<div style='clear: both' /></div>";
		}
		$output .= "</div>\n";

		return $output;
	}

}
