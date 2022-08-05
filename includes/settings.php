<?php
if(!class_exists('NovaStreamSEO_Settings'))
{
    class NovaStreamSEO_Settings
    {
        const SLUG = "novastream-seo-options";

        /**
         * Construct the plugin object
         */
        public function __construct($plugin)
        {
            // register actions
            acf_add_options_page(array(
                'page_title' => __('SEO & Social Sharing Options', 'novastream-seo'),
                'menu_title' => __('SEO Options', 'novastream-seo'),
                'menu_slug' => self::SLUG,
                'capability' => 'manage_options',
                'redirect' => false,
                'icon_url' => 'dashicons-share'
            ));

            add_action('init', array(&$this, "init"));
            add_action('admin_menu', array(&$this, 'admin_menu'), 20);
            add_filter("plugin_action_links_$plugin", array(&$this, 'plugin_settings_link'));


        } // END public function __construct

        /**
         * Add options page
         */
        public function admin_menu()
        {
            // Duplicate link into properties mgmt
            add_submenu_page(
                self::SLUG,
                __('Settings', 'custom'),
                __('Settings', 'custom'),
                'manage_options',
                self::SLUG,
                1
            );
        }

        /**
         * Add settings fields via ACF
         */
        public function init() {

            if( function_exists('acf_add_local_field_group') ):
                acf_add_local_field_group(array(
                    'key' => 'group_62ed13bfc141b',
                    'title' => 'SEO & Social Sharing Configuration',
                    'fields' => array(
                        array(
                            'key' => 'field_628e39fe55b08',
                            'label' => 'Social Sharing Defaults',
                            'name' => '',
                            'type' => 'tab',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'placement' => 'top',
                            'endpoint' => 0,
                        ),
                        array(
                            'key' => 'field_628e3a2755b09',
                            'label' => 'Image',
                            'name' => 'default_seo_image',
                            'type' => 'image',
                            'instructions' => 'Image size must be at least 200 x 200 pixels and not exceed 8 MB. The guide for developers recommends 1200 x 630 pixels, this size will allow for a high-quality display on devices with high resolution.',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'url',
                            'preview_size' => 'medium',
                            'library' => 'all',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                            'mime_types' => '',
                        ),
                        array(
                            'key' => 'field_628e3a3b55b0a',
                            'label' => 'Title',
                            'name' => 'default_seo_title',
                            'type' => 'text',
                            'instructions' => 'SEO experts recommend using a length of 40 - 60 characters',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => 'Explore',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_628e3a5755b0b',
                            'label' => 'Description',
                            'name' => 'default_seo_description',
                            'type' => 'textarea',
                            'instructions' => 'One or two short sentences. SEO experts recommend that you do not go beyond the limit of 200 characters',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => 'Explore the beautiful views Cape Breton has to offer.',
                            'maxlength' => '',
                            'rows' => '',
                            'new_lines' => '',
                        ),
                        array(
                            'key' => 'field_628e39fe50001',
                            'label' => 'Social Sharing Override Locations',
                            'name' => '',
                            'type' => 'tab',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'placement' => 'top',
                            'endpoint' => 0,
                        ),
                        array(
                            'key' => 'field_62ed13c8c8e6b',
                            'label' => 'Locations',
                            'name' => 'seo_locations',
                            'type' => 'checkbox',
                            'instructions' => 'Show SEO / Sharing fields in selected post types<br>This will allow you to override the default page title/description/image that is displayed on Google, Facebook, etc for selected posts/pages',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array(
                            ),
                            'allow_custom' => 0,
                            'default_value' => array(
                            ),
                            'layout' => 'vertical',
                            'toggle' => 0,
                            'return_format' => 'value',
                            'save_custom' => 0,
                        ),
                    ),
                    'location' => array (
                        array (
                            array (
                                'param' => 'options_page',
                                'operator' => '==',
                                'value' => self::SLUG,
                            ),
                        ),
                    ),
                    'menu_order' => 0,
                    'position' => 'normal',
                    'style' => 'default',
                    'label_placement' => 'top',
                    'instruction_placement' => 'label',
                    'hide_on_screen' => '',
                    'active' => true,
                    'description' => '',
                    'show_in_rest' => 0,
                ));

                acf_add_local_field_group(array(
                    'key' => 'group_62ec034dd774a',
                    'title' => 'SEO/Sharing',
                    'fields' => array(
                        array(
                            'key' => 'field_62ec0593d7d45',
                            'label' => '',
                            'name' => '',
                            'type' => 'message',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'message' => 'If filled these fields will be what displays on google search results or social media sharing.<br>The defaults can be set on the Site Options section of the site.',
                            'new_lines' => 'wpautop',
                            'esc_html' => 0,
                        ),
                        array(
                            'key' => 'field_62ec03ce911b7',
                            'label' => 'Title',
                            'name' => 'seo_title',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => 'Explore',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_62ec05caba705',
                            'label' => 'Description',
                            'name' => 'seo_description',
                            'type' => 'textarea',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => 'Explore the beautiful views Cape Breton has to offer.',
                            'maxlength' => '',
                            'rows' => 2,
                            'new_lines' => '',
                        ),
                        array(
                            'key' => 'field_62ec05d6ba706',
                            'label' => 'Image',
                            'name' => 'seo_image',
                            'type' => 'image_aspect_ratio_crop',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'crop_type' => 'aspect_ratio',
                            'aspect_ratio_width' => 1200,
                            'aspect_ratio_height' => 630,
                            'return_format' => 'url',
                            'preview_size' => 'medium',
                            'library' => 'all',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                            'mime_types' => '',
                        ),
                    ),
                    'location' => array(
                        array(
                            array(
                                'param' => 'seo',
                                'operator' => '=',
                                'value' => 'true',
                            ),
                        ),
                    ),
                    'menu_order' => 0,
                    'position' => 'side',
                    'style' => 'standard',
                    'label_placement' => 'top',
                    'instruction_placement' => 'label',
                    'hide_on_screen' => '',
                    'active' => true,
                    'description' => '',
                    'show_in_rest' => 0,
                ));
            endif;
        }

        /**
         * Add the settings link to the plugins page
         */
        public function plugin_settings_link($links)
        {
            $settings_link = sprintf('<a href="admin.php?page=%s">Settings</a>', self::SLUG);
            array_unshift($links, $settings_link);

            return $links;
        } // END public function plugin_settings_link($links)
    } // END class NovaStreamSEO_Settings
} // END if(!class_exists('NovaStreamSEO_Settings'))