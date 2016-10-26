<?php
defined( 'ABSPATH' ) or die();
class MASE_Ads_HTML {

    public static function init() {
        add_action('init', array('MASE_Ads_HTML', 'wp_action_init'));
    }

    public static function wp_action_init() {
        $labels = array(
            'name'               => _x( 'HTML Ads', MASE_TEXT_DOMAIN ),
            'singular_name'      => _x( 'HTML Ad', MASE_TEXT_DOMAIN ),
            'menu_name'          => _x( 'HTML Ads', MASE_TEXT_DOMAIN ),
            'name_admin_bar'     => _x( 'HTML Ad',  MASE_TEXT_DOMAIN ),
            'add_new'            => _x( 'Add New', MASE_TEXT_DOMAIN ),
            'add_new_item'       => __( 'Add New HTML Ad', MASE_TEXT_DOMAIN ),
            'new_item'           => __( 'New HTML Ad', MASE_TEXT_DOMAIN ),
            'edit_item'          => __( 'Edit HTML Ad', MASE_TEXT_DOMAIN ),
            'view_item'          => __( 'View HTML Ad', MASE_TEXT_DOMAIN ),
            'all_items'          => __( 'HTML Ads', MASE_TEXT_DOMAIN ),
            'search_items'       => __( 'Search HTML Ads', MASE_TEXT_DOMAIN ),
            'parent_item_colon'  => __( 'Parent HTML Ads:', MASE_TEXT_DOMAIN ),
            'not_found'          => __( 'No ads found.', MASE_TEXT_DOMAIN ),
            'not_found_in_trash' => __( 'No ads found in Trash.', MASE_TEXT_DOMAIN )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'moreAds SE Ads', MASE_TEXT_DOMAIN ),
            'public'             => false,
            'publicly_queryable' => false,
            'exclude_from_search'=> true,
            'show_ui'            => true,
            'show_in_menu'       => MASE_PREFIX.'menu',
            'show_in_nav_menus'  => false,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => MASE_PREFIX.'html_ads' ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 400,
            'supports'           => array('title', 'editor'),
            'register_meta_box_cb' => array('MASE_Ads_HTML', 'add_metaboxes')
        );


        register_post_type( MASE_PREFIX.'html_ads', $args);
    }


    public static function add_metaboxes() {
        add_meta_box(MASE_PREFIX.'geoip_1', __('Display in Country', MASE_TEXT_DOMAIN), array('MASE_Ads_MetaBoxes', 'metabox_geoip'), NULL, 'advanced', 'default');
        add_meta_box(MASE_PREFIX.'size_1', __('Ad Size', MASE_TEXT_DOMAIN), array('MASE_Ads_MetaBoxes', 'metabox_size'), NULL, 'side', 'low');
        add_meta_box(MASE_PREFIX.'device_2', __('Display on Device', MASE_TEXT_DOMAIN), array('MASE_Ads_MetaBoxes', 'metabox_device'), NULL, 'side', 'low');
        if(MASE_Pro::isVMTAPIActive() && MASE_Pro::isSubscriptionActive()) add_meta_box(MASE_PREFIX.'vmt_api', __('Display on Connection', MASE_TEXT_DOMAIN), array('MASE_Ads_MetaBoxes', 'metabox_vmt_api'), NULL, 'side', 'low');
        if(MASE_Pro::isSyncEnabled() && MASE_Pro::isSubscriptionActive()) add_meta_box(MASE_PREFIX.'sync', __('Global Ads', MASE_TEXT_DOMAIN), array('MASE_Ads_MetaBoxes', 'metabox_sync'), NULL, 'side', 'low');
        add_meta_box(MASE_PREFIX.'iframe_mode', __('IFrame Legacy Mode', MASE_TEXT_DOMAIN), array('MASE_Ads_MetaBoxes', 'metabox_iframe_mode'), NULL, 'side', 'low');
        add_meta_box(MASE_PREFIX.'disabled', __('Disable Ad', MASE_TEXT_DOMAIN), array('MASE_Ads_MetaBoxes', 'metabox_disabled'), NULL, 'side', 'low');
    }

}