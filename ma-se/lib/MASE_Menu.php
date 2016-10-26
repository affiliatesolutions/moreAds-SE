<?php
defined( 'ABSPATH' ) or die();
class MASE_Menu {

    protected static $fields = array();
    protected static $msg_sent_walker = false;

    public static function init() {
        if(MASE::$ZONE_MENU) {
            add_filter('wp_nav_menu_objects', array('MASE_Menu', 'wp_nav_menu_objects'), 10, 2);
            if(is_admin()) add_action('wp_loaded', array('MASE_Menu', 'wp_loaded'), 10000);
            if(is_admin()) add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, '_fields' ), 10, 4 );
            if(is_admin()) add_action( 'wp_update_nav_menu_item', array( __CLASS__, '_save' ), 10, 3 );
            if(is_admin()) add_filter( 'manage_nav-menus_columns', array( __CLASS__, '_columns' ), 99 );
        }

        self::$fields = array(
            'mase-is-menu-zone',
            'mase-devices',
            'mase-width',
            'mase-height'
        );
    }

    public static function wp_loaded() {
        if(MASE::$ZONE_MENU && is_admin()) {
            MASE::$ZONE_MENU = false;
            if(!class_exists('childwp/wp-admin/includes/class-walker-nav-menu-edit.php')) require_once ABSPATH.'/wp-admin/includes/nav-menu.php';
            $walker_class_name = apply_filters( 'wp_edit_nav_menu_walker', 'Walker_Nav_Menu_Edit', 0);
            MASE::$ZONE_MENU = true;
            if($walker_class_name == "Walker_Nav_Menu_Edit") {
                add_filter( 'wp_edit_nav_menu_walker', array('MASE_Menu', 'mase_menu_edit_walker'), 199 );
            } else {
                if(is_admin() && !MASE_Menu::$msg_sent_walker) {
                    MASE_Menu::$msg_sent_walker = true;
                    $reflector = new ReflectionClass($walker_class_name);
                    $plugin_theme_name = current(explode("/", plugin_basename($reflector->getFileName())));
                    MASE_Admin::add_admin_notice('update-nag', sprintf(__('The Plugin/Theme "%s" is not compatible with the moreAds SE Feature "Menu Link Zones". Please disable the Plugin/Theme or disable the moreAds SE Feature.', MASE_TEXT_DOMAIN), $plugin_theme_name));
                }
            }
        }
    }

    /**
     * Save custom field value
     *
     * @wp_hook action wp_update_nav_menu_item
     *
     * @param int   $menu_id         Nav menu ID
     * @param int   $menu_item_db_id Menu item ID
     * @param array $menu_item_args  Menu item data
     */
    public static function _save( $menu_id, $menu_item_db_id, $menu_item_args ) {
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            return;
        }

        check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );


        foreach ( self::$fields as $_key ) {

            $key = sprintf( 'menu-item-%s', $_key );

            // Sanitize
            if ( ! empty( $_POST[ $key ][ $menu_item_db_id ] ) ) {
                $value = $_POST[ $key ][ $menu_item_db_id ];
            }
            else {
                $value = null;
            }
            // Update
            if ( ! is_null( $value ) ) {
                update_post_meta( $menu_item_db_id, $key, $value );
            }
            else {
                delete_post_meta( $menu_item_db_id, $key );
            }
        }
    }
    /**
     * Print field
     *
     * @param object $item  Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args  Menu item args.
     * @param int    $id    Nav menu ID.
     *
     * @return string Form fields
     */
    public static function _fields( $id, $item, $depth, $args ) {

        $is_menu_zone = get_post_meta($item->ID, 'menu-item-mase-is-menu-zone', true);
        $devices = get_post_meta($item->ID, 'menu-item-mase-devices', true);
        $zone_ads_count = MASE::GetZoneCount(MASE_PREFIX.'menu_zone_ads_'.$item->ID);

        if(empty($devices)) {
            $devices = array(MASE_DEVICE_TABLET, MASE_DEVICE_DESKTOP, MASE_DEVICE_MOBILE);
        }

        require MASE_DIR.'/lib/Pages/Menu.php';
    }
    /**
     * Add our fields to the screen options toggle
     *
     * @param array $columns Menu item columns
     * @return array
     */
    public static function _columns( $columns ) {
        $columns = array_merge( $columns, self::$fields );
        return $columns;
    }

    public static function mase_menu_edit_walker($walker = '', $menu_id = '') {
        if(MASE::$ZONE_MENU) {
            $walker = 'MASE_Walker_Nav_Menu_Edit';
            if ( ! class_exists( $walker ) ) {
                require_once(MASE_DIR.'/lib/MASE_Walker_Nav_Menu_Edit.php');
            }
        }
        return $walker;
    }


    public static function wp_nav_menu_objects($menu_items, $args) {
        if(MASE::$ZONE_MENU) {
            foreach ($menu_items as &$item) {
                $res = get_post_meta($item->ID, 'menu-item-mase-is-menu-zone', true);
                if (!empty($res)) {
                    $item->url = get_admin_url(null, 'admin-ajax.php') . "?action=mase_menu_redirect&id=" . $item->ID . '&mid=' . $args->menu->slug;
                }
            }
        }
        return $menu_items;
    }
}