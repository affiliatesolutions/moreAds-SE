<?php
defined( 'ABSPATH' ) or die();
class MASE_Ads_Popup {

    public static function init() {
        add_action('init', array('MASE_Ads_Popup', 'wp_action_init'));
    }

    public static function wp_action_init() {
        $labels = array(
            'name'               => _x( 'Popup Ads', MASE_TEXT_DOMAIN ),
            'singular_name'      => _x( 'Popup Ad', MASE_TEXT_DOMAIN ),
            'menu_name'          => _x( 'Popup Ads', MASE_TEXT_DOMAIN ),
            'name_admin_bar'     => _x( 'Popup Ad',  MASE_TEXT_DOMAIN ),
            'add_new'            => _x( 'Add New', MASE_TEXT_DOMAIN ),
            'add_new_item'       => __( 'Add New Popup Ad', MASE_TEXT_DOMAIN ),
            'new_item'           => __( 'New Popup Ad', MASE_TEXT_DOMAIN ),
            'edit_item'          => __( 'Edit Popup Ad', MASE_TEXT_DOMAIN ),
            'view_item'          => __( 'View Popup Ad', MASE_TEXT_DOMAIN ),
            'all_items'          => __( 'Popup Ads', MASE_TEXT_DOMAIN ),
            'search_items'       => __( 'Search Popup Ads', MASE_TEXT_DOMAIN ),
            'parent_item_colon'  => __( 'Parent Popup Ads:', MASE_TEXT_DOMAIN ),
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
            'rewrite'            => array( 'slug' => MASE_PREFIX.'popup_ads' ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 400,
            'supports'           => array('title'),
            'register_meta_box_cb' => array('MASE_Ads_Popup', 'add_metaboxes')
        );


        register_post_type( MASE_PREFIX.'popup_ads', $args);
    }

    public static function add_metaboxes() {
        add_meta_box(MASE_PREFIX.'geoip_1', __('Display in Country', MASE_TEXT_DOMAIN), array('MASE_Ads_MetaBoxes', 'metabox_geoip'), NULL, 'advanced', 'default');
        add_meta_box(MASE_PREFIX.'device_2', __('Display on Device', MASE_TEXT_DOMAIN), array('MASE_Ads_MetaBoxes', 'metabox_device'), NULL, 'side', 'low');
        add_meta_box(MASE_PREFIX.'url_1', __('Target URL', MASE_TEXT_DOMAIN), array('MASE_Ads_MetaBoxes', 'metabox_target_url'), NULL, 'normal', 'default');
        if(MASE_Pro::isVMTAPIActive() && MASE_Pro::isSubscriptionActive()) add_meta_box(MASE_PREFIX.'vmt_api', __('Display on Connection', MASE_TEXT_DOMAIN), array('MASE_Ads_MetaBoxes', 'metabox_vmt_api'), NULL, 'side', 'low');
        if(MASE_Pro::isSubscriptionActive() && MASE_Pro::isSyncEnabled()) add_meta_box(MASE_PREFIX.'sync', __('Global Ads', MASE_TEXT_DOMAIN), array('MASE_Ads_MetaBoxes', 'metabox_sync'), NULL, 'side', 'low');
        add_meta_box(MASE_PREFIX.'disabled', __('Disable Ad', MASE_TEXT_DOMAIN), array('MASE_Ads_MetaBoxes', 'metabox_disabled'), NULL, 'side', 'low');
    }

}