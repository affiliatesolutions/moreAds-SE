<?php
defined( 'ABSPATH' ) or die();

class MASE_Widgets {
    public static $widget_ids = array('mase_banner_widget', 'mase_popup_widget', 'mase_float_widget');

    public static function init() {
        add_action( 'widgets_init', array('MASE_Widgets', 'wp_action_register_widgets') );
        if(is_admin() ) add_action( 'wp_ajax_save-widget', array('MASE_Widgets', 'wp_save_widget'), 0 );
    }

    public static function wp_action_register_widgets() {
        require_once(MASE_DIR.'lib/Widgets/Banner.php');
        register_widget('MASE_Banner_Widget');

        require_once(MASE_DIR.'lib/Widgets/Popup.php');
        register_widget('MASE_Popup_Widget');

        require_once(MASE_DIR.'lib/Widgets/TextLink.php');
        register_widget('MASE_TextLink_Widget');

        require_once(MASE_DIR.'lib/Widgets/Float.php');
        register_widget('MASE_Float_Widget');

        require_once(MASE_DIR.'lib/Widgets/ExitIntent.php');
        register_widget('MASE_ExitIntent_Widget');
    }

    public static function wp_save_widget() {
        if(isset($_REQUEST['delete_widget']) && $_REQUEST['delete_widget'] == 1) {
            if(in_array($_REQUEST['id_base'], MASE_Widgets::$widget_ids)) {
                if($_REQUEST['id_base'] == 'mase_banner_widget') {
                    $zone_type_key = 'banner_zone_ads_';
                } elseif($_REQUEST['id_base'] == 'mase_float_widget') {
                    $zone_type_key = 'float_zone_ads_';
                } elseif($_REQUEST['id_base'] == 'mase_textlink_widget') {
                    $zone_type_key = 'textlink_zone_ads_';
                } else { // mase_popup_widget
                    $zone_type_key = 'popup_zone_ads_';
                }

                $widget_nr = $_REQUEST['widget_number'];
                if(isset($_REQUEST['multi_number']) && !empty($_REQUEST['multi_number'])) $widget_nr = $_REQUEST['multi_number'];

                $zone_identifier = MASE_PREFIX.$zone_type_key.intval($widget_nr);
                delete_option($zone_identifier);
            }
        }
    }
}