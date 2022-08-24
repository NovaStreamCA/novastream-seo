<?php
/**
 * @package Novastream SEO
 * @version 1.0.2
 */
/*
Plugin Name: Novastream SEO
Plugin URI: https://novastream.ca
Description: NovaStream's SEO Plugin
Author: NovaStream
Version: 1.0.2
Author URI: https://novastream.ca
*/

if(!class_exists("NovaStreamSEO"))
{
    /**
     * class:   NovaStreamSEO
     * desc:    plugin displays SEO overrides on selected post types
     */
    class NovaStreamSEO
    {
        /**
         * Created an instance of the NovaStreamSEO class
         */
        public function __construct()
        {
            // Set up ACF
            add_filter('acf/settings/path', function() {
                return sprintf("%s/includes/advanced-custom-fields-pro/", dirname(__FILE__));
            });
            add_filter('acf/settings/dir', function() {
                return sprintf("%s/includes/advanced-custom-fields-pro/", plugin_dir_url(__FILE__));
            });
            require_once(sprintf("%s/includes/advanced-custom-fields-pro/acf.php", dirname(__FILE__)));

            // Settings managed via ACF
            require_once(sprintf("%s/includes/settings.php", dirname(__FILE__)));
            $settings = new NovaStreamSEO_Settings(plugin_basename(__FILE__));

            //Github plugin updater
            require 'plugin-update-checker.php';
            $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
                'https://github.com/NovaStreamCA/novastream-seo',
                __FILE__,
                'novastream-seo'
            );
            $myUpdateChecker->setBranch('main');

            // Add ACF Filters
            add_filter('acf/load_field/name=seo_locations', 'acf_load_post_types');
            add_filter('acf/location/rule_types', 'acf_location_rules_types');
            add_filter('acf/location/rule_operators', 'acf_location_rules_operators');
            add_filter('acf/location/rule_values/seo', 'acf_location_rule_values_seo');
            add_filter('acf/location/rule_match/seo', 'acf_location_rule_match_seo', 10, 4);
            add_action( 'admin_head', 'seo_style' );
            add_action('wp_head', 'novastream_seo', 5);
        } // END public function __construct()
    } // END class NovaStreamSEO
} // END if(!class_exists("NovaStreamSEO"))

if(class_exists('NovaStreamSEO')) {

    // instantiate the plugin class
    $plugin = new NovaStreamSEO();

} // END if(class_exists('NovaStreamSEO'))

// ACF to show all post types as checkbox
function acf_load_post_types($field) {
    foreach ( get_post_types( array( 'show_in_nav_menus' => true), 'objects' ) as $post_type ) {
       $field['choices'][$post_type->name] = $post_type->labels->singular_name;
    }
    return $field;
}

// ACF rule for where to show SEO fields
function acf_location_rules_types( $choices ) {

    $choices['Basic']['seo'] = 'SEO';

    return $choices;

}

function acf_location_rules_operators( $choices ) {

    $choices['='] = 'is selected';

    return $choices;

}

function acf_location_rule_values_seo( $choices ) {

    $choices[ 'true' ] = 'True';

    return $choices;
}

// If post type is selected it will display the SEO fields
function acf_location_rule_match_seo( $match, $rule, $options, $field_group ) {
    $paramaters = get_field('seo_locations', 'option');
    $postType = get_post_type();

    if($paramaters && is_array($paramaters)) {
        if(in_array($postType, $paramaters)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// Apply Open Graph
function novastream_seo() {
    global $post;

    if($post) {
        $postID = $post->ID;

        if(get_field('seo_image', $postID)) {
            $image = get_field('seo_image', $postID);
        } else if(has_post_thumbnail($postID)) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id( $postID ), 'medium');
        } else {
            $image = get_field('default_seo_image', 'option');
        }

        if(get_field('seo_description', $postID)) {
            $excerpt = get_field('seo_description', $postID);
        } else if($excerpt = get_the_excerpt($postID)) {
            $excerpt = strip_tags($excerpt);
            $excerpt = str_replace("", "'", $excerpt);
        } else {
            $excerpt = get_field('default_seo_description', 'option');
        }

        if(get_field('seo_title', $postID)) {
            $title = get_field('seo_title', $postID);
        } else if(get_the_title($postID)) {
            $title = get_the_title($postID);
        } else {
            $title = get_field('default_seo_title', 'option');
        }

        if(is_front_page()) {
            $title = get_bloginfo();
        }

        echo '<meta name="description" content="' . $excerpt . '">';
        echo '<meta property="og:title" content="'.$title.'"/>';
        echo '<meta property="og:description" content="'.$excerpt.'"/>';
        echo '<meta property="og:url" content="'.get_the_permalink($postID).'"/>';
        echo '<meta property="og:site_name" content="'.get_bloginfo().'"/>';
        if($image) {
            echo '<meta property="og:image" content="'.$image.'"/>';
        }
    }
}

// Add SEO Styles
function seo_style() {
	wp_enqueue_style( 'seo-css', plugins_url('/includes/styles/seo.css', __FILE__), array(), '1.0.0' );
}
