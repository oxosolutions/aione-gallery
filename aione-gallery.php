<?php
/*
	Plugin Name: Aione Gallery
	Description: All in one Gallery
	Plugin URI: http://oxosolutions.com/products/wordpress-plugins/aione/
	Version: 1.0.0.0
	Author: OXO Solutions
	Author URI: http://oxosolutions.com/
*/

//Remove Default Gallery styles
//add_filter( 'use_default_gallery_style', '__return_false' );
//add_image_size( 'gallery', 400, 400, true );

//Add HTML5 Element Support to Theme
//add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
remove_shortcode( 'gallery' );
add_shortcode('gallery', 'aione_gallery_shortcode');


/* ====================== ADD Style ========================= */

add_action('wp_print_styles', 'aione_gallery_register_styles');
function aione_gallery_register_styles() {
    wp_register_style('aione.gallery.styles', plugins_url('/aione-gallery/assets/css/style.css'));
    wp_enqueue_style('aione.gallery.styles');
} 

/* ====================== ADD Style ========================= */


/* =================================================== Main Function =========================================== */
function aione_gallery_shortcode( $attr ) {
	/*
	$post = get_post();
	static $instance = 0;
	$instance++;
	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) ) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}
	$output = apply_filters( 'post_gallery', '', $attr, $instance );
	if ( $output != '' ) {
		return $output;
	}
	*/
	
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
	
	
	
	
	

	/*
	$id = intval( $atts['id'] );
	if ( ! empty( $atts['include'] ) ) {
		$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	} else {
		$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	}
	if ( empty( $attachments ) ) {
		return '';
	}
	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
		}
		return $output;
	}
	*/
	
	
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