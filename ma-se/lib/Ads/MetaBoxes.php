<?php

class MASE_Ads_MetaBoxes {

    public static function metabox_target_url() {
        global $post;
        echo '<input type="hidden" name="ad_nonce" id="ad_nonce" value="' .
            wp_create_nonce( 'ad_save' ) . '" />'; // popup, banner
        $_target_url = get_post_meta($post->ID, '_target_url', true);
        echo '<input type="text" name="_target_url" value="' . $_target_url  . '" class="widefat" />';
    }

    public static function metabox_graphic() {
        global $post;
        $_media_id = get_post_meta($post->ID, '_media_id', true);
        $media = wp_get_attachment_image_src($_media_id, 'full', false);
        $_media_url = !empty($media) && isset($media[0]) ? $media[0] : false;
        require_once MASE_DIR.'/lib/Ads/html/imageselect.php';
    }

    public static function metabox_device() {
        global $post;
        $_devices = get_post_meta($post->ID, '_devices', true);
        if(empty($_devices)) $_devices = MASE_DEVICE_MOBILE.','.MASE_DEVICE_DESKTOP.','.MASE_DEVICE_TABLET;
        $_selected_devices = explode(",", $_devices);
        require_once MASE_DIR.'/lib/Ads/html/device.php';
    }

    public static function metabox_geoip() {
        global $post;
        $_selected_geoip = explode(",", get_post_meta($post->ID, '_geoip', true));
        require_once MASE_DIR.'/lib/Ads/html/geoip.php';
    }

    public static function metabox_vmt_api() {
        global $post;
        $_connections = get_post_meta($post->ID, '_connection_ids', true);
        if(empty($_connections)) $_connections = MASE_CONNECTION_3G.','.MASE_CONNECTION_WIFI;
        $_selected_connections = explode(",", $_connections);
        require_once MASE_DIR.'/lib/Ads/html/connections.php';
    }

    public static function metabox_size() {

        echo '<input type="hidden" name="ad_nonce" id="ad_nonce" value="' .
            wp_create_nonce( 'ad_save' ) . '" />'; // html

        global $post;
        $_selected_size = get_post_meta($post->ID, '_media_size', true);
        $size_info = explode("x", $_selected_size);
        $_selected_width = intval($size_info[0]);
        $_selected_height = intval($size_info[1]);

        require_once MASE_DIR.'/lib/Ads/html/size.php';
    }

    public static function metabox_sync() {
        global $post;
        $_sync = get_post_meta($post->ID, '_sync', true);
        require_once MASE_DIR.'/lib/Ads/html/sync.php';
    }

    public static function metabox_iframe_mode() {
        global $post;
        $_iframe_mode = get_post_meta($post->ID, '_iframe_mode', true);
        require_once MASE_DIR.'/lib/Ads/html/iframe_mode.php';
    }

    public static function metabox_disabled() {
        global $post;
        $_disabled = get_post_meta($post->ID, '_disabled', true);
        require_once MASE_DIR.'/lib/Ads/html/disabled.php';
    }

    public static function metabox_reallink() {
        global $post;
        $_show_real_link = get_post_meta($post->ID, '_show_real_link', true);
        require_once MASE_DIR.'/lib/Ads/html/showreallink.php';
    }


}