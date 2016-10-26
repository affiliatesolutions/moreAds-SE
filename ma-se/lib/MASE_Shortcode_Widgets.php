<?php

class MASE_Shortcode_Widgets {
    private static $initiated = false;

    /**
     * @var array
     */
    public static $SideBars = false;

    public static function init() {
        if(!self::$initiated) {
            self::init_real();
        }
    }

    public static function get_sidebars() {
        $data = maybe_unserialize(get_option(MASE_PREFIX.'widget_areas'));
        if($data) {
            return $data;
        }
        return false;
    }

    public static function set_sidebars($data) {
        if(is_array($data)) {
            update_option(MASE_PREFIX.'widget_areas', serialize($data));
            return true;
        }
        return false;
    }

    private static function init_real() {
        self::$initiated = true;
        self::$SideBars = self::get_sidebars();
        add_shortcode( 'mase_widget_area', array( 'MASE_Shortcode_Widgets', 'SidebarShortcode' ) );
        add_action( 'widgets_init', array('MASE_Shortcode_Widgets', 'initShortCodeWidgets') );
    }


    public static function initShortCodeWidgets() {
        foreach ((array)self::$SideBars as $sidebar) {
            if (!isset($sidebar['name']) || !isset($sidebar['key'])) continue;
            register_sidebar(array(
                'name' => sprintf(__('moreAds SE Shortcode-Area %s', MASE_PREFIX), $sidebar['name']),
                'id' => 'mase_widget_area_' . $sidebar['key'],
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '',
                'after_title' => '',
            ));
        }
    }


    public static function SidebarShortcode( array $atts ) {
        extract( shortcode_atts( array( 'name' => '1', 'text' => '0' ), $atts ) );

        $back =  "<span class='" . str_replace( " ", "_", $name ) . "' class='sidebar_shortcode'>";
        ob_start();
        if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'mase_widget_area_'.$name ) ) {}
        $back .= ob_get_contents();
        ob_end_clean();
        $back .= "</span>";
        return $back;
    }

    public static function addShortCode($data) {
        $name = preg_replace("/[^a-z0-9-_]/i", "", htmlspecialchars($data['name']));
        if(@!in_array(@strtolower($name), MASE_Shortcode_Widgets::$SideBars)) {
            self::$SideBars[strtolower($name)] = array(
                'name' => $data['name'],
                'key' => strtolower($name),
                'created' => time()
            );
            self::set_sidebars(MASE_Shortcode_Widgets::$SideBars);
            return true;
        }
        return false;
    }
}