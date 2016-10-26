<?php

class MASE_Ads_TagTaxonomy {
    public static function init() {
        add_action('init', array('MASE_Ads_TagTaxonomy', 'wp_action_register_tag_taxonomy'));
    }

    public static function wp_action_register_tag_taxonomy() {
        register_taxonomy(
            MASE_PREFIX.'ad_tags',
            MASE_PREFIX.'banner_ads',
            array(
                'label' => __( 'Ad-Tags', MASE_TEXT_DOMAIN ),
                'hierarchical' => false,
                'show_in_nav_menus' => false,
                'rewrite' => array( 'slug' => 'ad_tags' ),
                'capabilities' => array(
                    'assign_terms' => true,
                    'edit_terms' => true,
                    'delete_terms' => true,
                    'manage_terms' => true
                ),
            )
        );
        register_taxonomy_for_object_type(MASE_PREFIX.'ad_tags', MASE_PREFIX.'banner_ads');
        register_taxonomy_for_object_type(MASE_PREFIX.'ad_tags', MASE_PREFIX.'popup_ads');
        register_taxonomy_for_object_type(MASE_PREFIX.'ad_tags', MASE_PREFIX.'html_ads');
    }
}