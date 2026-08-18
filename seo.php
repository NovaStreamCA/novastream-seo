<?php
/**
 * @package Novastream SEO
 * @version 1.2.0
 */
/*
Plugin Name: Novastream SEO
Plugin URI: https://novastream.ca
Description: NovaStream's SEO Plugin
Author: NovaStream
Version: 1.2.0
Update URI: https://github.com/NovaStreamCA/novastream-seo
Requires Plugins: advanced-custom-fields-pro
Author URI: https://novastream.ca
Text Domain: novastream-seo
*/

require_once plugin_dir_path( __FILE__ ) . 'plugin-update-checker.php';

$novastream_seo_update_checker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/NovaStreamCA/novastream-seo',
    __FILE__,
    'novastream-seo'
);
$novastream_seo_update_checker->setBranch( 'main' );

/**
 * Determine whether the external ACF Pro dependency is active and usable.
 */
function novastream_seo_has_acf_pro() {
    return function_exists( 'acf_add_local_field_group' )
        && function_exists( 'acf_add_options_page' )
        && function_exists( 'get_field' );
}

/**
 * Stop activation on WordPress versions that do not enforce Requires Plugins.
 */
function novastream_seo_activate() {
    if ( novastream_seo_has_acf_pro() ) {
        return;
    }

    deactivate_plugins( plugin_basename( __FILE__ ) );

    wp_die(
        esc_html__( 'NovaStream SEO requires Advanced Custom Fields Pro to be installed and active.', 'novastream-seo' ),
        esc_html__( 'Plugin dependency missing', 'novastream-seo' ),
        array( 'back_link' => true )
    );
}
register_activation_hook( __FILE__, 'novastream_seo_activate' );

/**
 * Explain a missing dependency if ACF Pro is removed outside WordPress.
 */
function novastream_seo_acf_notice() {
    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }

    printf(
        '<div class="notice notice-error"><p>%s</p></div>',
        esc_html__( 'NovaStream SEO is inactive because Advanced Custom Fields Pro is not installed and active.', 'novastream-seo' )
    );
}

add_action( 'init', function() {
    load_plugin_textdomain( 'novastream-seo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

    if ( ! novastream_seo_has_acf_pro() ) {
        add_action( 'admin_notices', 'novastream_seo_acf_notice' );
        add_action( 'network_admin_notices', 'novastream_seo_acf_notice' );
        return;
    }

    require_once plugin_dir_path( __FILE__ ) . 'includes/settings.php';

    if ( class_exists( 'NovaStreamSEO' ) ) {
        new NovaStreamSEO();
    }
}, 5 );

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
            new NovaStreamSEO_Settings(plugin_basename(__FILE__));

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

         // Override if WooCommerce Shop Page
        if (function_exists('is_shop') && is_shop()) {
            $postID = wc_get_page_id('shop');
        }

        // Get SEO Image
        if (get_field('seo_image', $postID)) {
            $image = get_field('seo_image', $postID);
        } elseif (has_post_thumbnail($postID) && get_the_post_thumbnail_url($postID, 'full')) {
            $image = get_the_post_thumbnail_url($postID, 'full');
        } else {
            $image = get_field('default_seo_image', 'option');
        }

        // Get SEO Description
        if (get_field('seo_description', $postID)) {
            $excerpt = get_field('seo_description', $postID);
        } elseif ($excerpt = get_the_excerpt($postID)) {
            $excerpt = strip_tags($excerpt);
        } else {
            $excerpt = get_field('default_seo_description', 'option');
        }
        $excerpt = str_replace('"', "'", (string) ($excerpt ?? '')); // Replace double quotes with single quotes

        // Get SEO Title
        if (get_field('seo_title', $postID)) {
            $title = get_field('seo_title', $postID);
        } elseif (get_the_title($postID)) {
            $title = get_the_title($postID);
        } else {
            $title = get_field('default_seo_title', 'option');
        }
        $title = str_replace('"', "'", (string) ($title ?? '')); // Replace double quotes with single quotes

        // Get Front Page Title
        if (is_front_page()) {
            $title = get_bloginfo();
        }

        // Get Permalink
        $permalink = get_the_permalink($postID);

        // Override if WooCommerce Category Page
        if (function_exists('is_product_category') && is_product_category()) {
            $term = get_queried_object();
            if ($term->term_id) {
                if ($term->name) {
                    $title = $term->name;
                    $title = str_replace('"', "'", $title); // Replace double quotes with single quotes
                }

                if ($term->description) {
                    $excerpt = $term->description;
                } else {
                    $excerpt = get_field('default_seo_description', 'option');
                }
                $excerpt = str_replace('"', "'", (string) ($excerpt ?? '')); // Replace double quotes with single quotes

                if (get_term_link($term->term_id, 'product_cat')) {
                    $permalink = get_term_link($term->term_id, 'product_cat');
                }

                if ($thumbnailId = get_term_meta($term->term_id, 'thumbnail_id', true)) {
                    $image = wp_get_attachment_url($thumbnailId);
                } else {
                    $image = get_field('default_seo_image', 'option');
                }
            }
        }

		/**
         * Filters the final image URL used for Open Graph metadata.
         *
         * @param string|false $image  Selected social image URL.
         * @param int          $postID Post ID used to build the metadata.
         */
        $image = apply_filters('novastream_seo_social_image', $image, $postID);

        // Only render tags with meaningful server-side values.
        if ( $excerpt !== '' ) {
            printf( '<meta name="description" content="%s">', esc_attr( $excerpt ) );
            printf( '<meta property="og:description" content="%s">', esc_attr( $excerpt ) );
        }

        if ( $title !== '' ) {
            printf( '<meta property="og:title" content="%s">', esc_attr( $title ) );
        }

        if ( $permalink ) {
            printf( '<meta property="og:url" content="%s">', esc_url( $permalink ) );
        }

        if ( get_bloginfo() ) {
            printf( '<meta property="og:site_name" content="%s">', esc_attr( get_bloginfo() ) );
        }

        if ($image) {
            printf( '<meta property="og:image" content="%s">', esc_url( $image ) );
        }
    }
}

// Add SEO Styles
function seo_style() {
	wp_enqueue_style( 'seo-css', plugins_url('/includes/styles/seo.css', __FILE__), array(), '1.0.0' );
}
