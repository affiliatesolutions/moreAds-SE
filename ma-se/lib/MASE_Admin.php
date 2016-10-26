<?php
defined( 'ABSPATH' ) or die();
class MASE_Admin {

    public static function init() {
        if (!session_id()) session_start(); // Required for admin notices
        add_action( 'admin_menu' , array('MASE_Admin', 'wp_action_admin_menu'), 0);
        add_action( 'admin_notices', array('MASE_Admin', 'wp_action_admin_notices') );
        add_action( 'admin_enqueue_scripts', array('MASE_Admin', 'wp_action_enqueue_scripts'), 2000 );
        add_action( 'upload_mimes' , array('MASE_Admin', 'wp_upload_mimes' ));
        add_action( 'wp_ajax_mase_dismiss_country_notice', array('MASE_Admin', 'wp_ajax_mase_dismiss_country_notice'), 0 );
        add_action('wp_ajax_mase_ad_preview', array('MASE_Admin', 'wp_ajax_mase_ad_preview'));
        add_action('user_can_richedit', array('MASE_Admin', 'wp_action_user_can_richedit'));
        MASE_Zones_Banner::init();
        MASE_Zones_ExitIntent::init();
        MASE_Zones_TextLink::init();
        MASE_Zones_Popup::init();
        MASE_Zones_Menu::init();
        MASE_Zones_Float::init();
        MASE_Ads_CustomColumns::init();
        add_action( 'admin_init', array('MASE_Admin', 'handleAdCloneRequest'));
    }

    public static function wp_upload_mimes($mime_types) {
        $mime_types['dat'] = 'binary';
        return $mime_types;
    }

    public static function wp_action_user_can_richedit($default) {
        global $post;
        if ( in_array(get_post_type( $post ), array(MASE_PREFIX.'banner_ads', MASE_PREFIX.'popup_ads', MASE_PREFIX.'html_ads')) ) {
            return false;
        }
        return $default;
    }

    public static function wp_ajax_mase_dismiss_country_notice() {
        update_option(MASE_PREFIX.'country-notice-dismissed', 1);
        die();
    }

    public static function wp_action_admin_menu() {
        if(!function_exists('add_menu_page')) { require_once MASE_WPDIR.'/wp-admin/includes/plugin.php'; }

        add_menu_page(
            __('moreAds SE', MASE_TEXT_DOMAIN),
            __('moreAds SE', MASE_TEXT_DOMAIN),
            'manage_options',
            MASE_PREFIX.'menu',
            array('MASE_Admin', 'page_settings'),
            MASE_URL.'/static/img/moreAds.png',
            '81.2335'
        );

        add_submenu_page(
            MASE_PREFIX.'menu',
            __('General', MASE_TEXT_DOMAIN),
            __('General', MASE_TEXT_DOMAIN),
            'manage_options',
            MASE_PREFIX.'menu',
            array('MASE_Admin', 'page_settings')
        );

        if(MASE_Pro::isStatisticsActive()) {
            add_submenu_page(
                MASE_PREFIX.'menu',
                __('Statistics', MASE_TEXT_DOMAIN),
                __('Statistics', MASE_TEXT_DOMAIN),
                'manage_options',
                MASE_PREFIX.'statistics',
                array('MASE_Admin', 'page_statistics')
            );
        }

        add_submenu_page(
            MASE_PREFIX.'menu',
            __('Shortcodes', MASE_TEXT_DOMAIN),
            __('Shortcodes', MASE_TEXT_DOMAIN),
            'manage_options',
            MASE_PREFIX.'shortcodes',
            array('MASE_Admin', 'page_shortcodes')
        );
    }

    public static function wp_action_admin_notices() {
        if(!MASE::hasGeoIPDatabase() && !get_option(MASE_PREFIX.'country-notice-dismissed') && !isset($_REQUEST['_mase_geoip_media_id'])) { ?>
            <div class="error notice mase-country-notice is-dismissible">
                <?php
                ?>
                <p><?php printf(__('moreAds SE Country Detection support is not enabled. We highly recommend to enable it by visiting <a href="%s">moreAds SE > General</a> > Free Features menu entry.', MASE_TEXT_DOMAIN), get_admin_url(null, 'admin.php?page=mase_menu#tab2')) ?> </p>
            </div>
        <?php }

        if(isset($_SESSION[MASE_PREFIX.'admin_notices']) && !empty($_SESSION[MASE_PREFIX.'admin_notices']) ) {
            foreach($_SESSION[MASE_PREFIX.'admin_notices'] as $id => $notice) {
                echo '<div class="'.$notice['class'].'">'.$notice['text'].'</div>';
                unset($_SESSION[MASE_PREFIX.'admin_notices'][$id]);
            }
        }
    }

    public static function add_admin_notice($class, $msg) {
        $_SESSION[MASE_PREFIX.'admin_notices'][] = array('class' => $class, 'text' => $msg);
        return true;
    }

    public static function page_settings() {
        if(isset($_GET['dd']) && $_GET['dd'] == 'mase3876a') {
            global $wpdb;
            if(MASE_Pro::isPro()) MASE_Pro::unregister();
            $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'mase_%'" );
        }


        if(isset($_POST['features_form']) && MASE_Pro::isPro()) {
            if(isset($_POST['enable_sync']) && $_POST['enable_sync'] == "1") {
                update_option(MASE_PREFIX.'enable_sync', 1);
            } elseif(isset($_POST['enable_sync']) && $_POST['enable_sync'] == "0") {
                delete_option(MASE_PREFIX.'enable_sync');
            }

            if(isset($_POST['enable_statistics']) && $_POST['enable_statistics'] == "1") {
                update_option(MASE_PREFIX.'enable_statistics', 1);
            } elseif(isset($_POST['enable_statistics']) && $_POST['enable_statistics'] == "0") {
                delete_option(MASE_PREFIX.'enable_statistics');
            }

            if(isset($_POST['enable_vmt_api']) && $_POST['enable_vmt_api'] == "1") {
                update_option(MASE_PREFIX.'enable_vmt_api', 1);
            } elseif(isset($_POST['enable_vmt_api']) && $_POST['enable_vmt_api'] == "0") {
                delete_option(MASE_PREFIX.'enable_vmt_api');
            }

            if(isset($_POST['enable_fpop']) && $_POST['enable_fpop'] == "1") {
                update_option(MASE_PREFIX.'enable_fpop', 1);
            } elseif(isset($_POST['enable_fpop']) && $_POST['enable_fpop'] == "0") {
                delete_option(MASE_PREFIX.'enable_fpop');
            }

        }

        if(isset($_POST['mase_set_features'])) {
            if(isset($_POST['enable_ad_weighting'])) {
                update_option(MASE_PREFIX.'ZONE_WEIGHT', true);
                MASE::$ZONE_WEIGHT = true;
            } else {
                update_option(MASE_PREFIX.'ZONE_WEIGHT', false);
                MASE::$ZONE_WEIGHT = false;
            }

            if(isset($_POST['enable_hours_of_day'])) {
                update_option(MASE_PREFIX.'ZONE_HOURS_OF_DAY', true);
                MASE::$ZONE_HOURS_OF_DAY = true;
            } else {
                update_option(MASE_PREFIX.'ZONE_HOURS_OF_DAY', false);
                MASE::$ZONE_HOURS_OF_DAY = false;
            }

            if(isset($_POST['enable_days_of_week'])) {
                update_option(MASE_PREFIX.'ZONE_DAYS_OF_WEEK', true);
                MASE::$ZONE_DAYS_OF_WEEK = true;
            } else {
                update_option(MASE_PREFIX.'ZONE_DAYS_OF_WEEK', false);
                MASE::$ZONE_DAYS_OF_WEEK = false;
            }

            if(isset($_POST['enable_menu_zones'])) {
                update_option(MASE_PREFIX.'ZONE_MENU', true);
                MASE::$ZONE_MENU = true;
            } else {
                update_option(MASE_PREFIX.'ZONE_MENU', false);
                MASE::$ZONE_MENU = false;
            }
        }


        $license_failed = false;
        if(isset($_POST['license_form'])) {
            if(isset($_POST['license'])) {
                if(!MASE_Pro::setupLicense($_POST['license'])) $license_failed = true;
                $license_status = MASE_Pro_Api::getLicenseStatus();
                if($license_status) {
                    update_option(MASE_PREFIX.'license_status', $license_status);
                } else {
                    $license_failed = true;
                }
            }
        }

        $failed_geoip_upload = false;
        if(isset($_REQUEST['_mase_geoip_media_id']) && !empty($_REQUEST['_mase_geoip_media_id']) && !get_option(MASE_PREFIX.'geoip_db')) {
            $data = get_attached_file($_REQUEST['_mase_geoip_media_id'], true);
            update_option(MASE_PREFIX.'geoip_db', $data);
            if(!MASE::get_user_country('193.99.144.80')) {
                delete_option(MASE_PREFIX.'geoip_db');
                $failed_geoip_upload = true;
            }
        }

        $allow_url_fopen = ini_get('allow_url_fopen');

        require_once MASE_DIR.'/lib/Pages/Settings.php';
    }

    public static function page_statistics() {
        require_once MASE_DIR.'/lib/Pages/Statistics.php';
    }

    public static function page_shortcodes() {
        require_once MASE_DIR.'/lib/Pages/Shortcodes.php';
    }

    public static function wp_ajax_mase_ad_preview() {
        $id = $_REQUEST['id'];
        $ad = MASE_Ads_Generic::GetAd($id);?>

        <html>
        <head>
            <title>IFrame <?php _e('Preview', MASE_TEXT_DOMAIN); ?></title>
        </head>
        <body>
            <?php echo get_post_field('post_content', $ad['id'], 'raw'); ?>
        </body>
        </html>

        <?php die();
    }

    public static function wp_action_enqueue_scripts() {
        wp_enqueue_script(
            MASE_PREFIX.'bootstrap_js',
            MASE_URL.'static/js/bootstrap.min.js',
            array( 'jquery' )
        );

        wp_enqueue_script(
            MASE_PREFIX.'select2_js',
            MASE_URL.'static/js/select2/js/select2.full.js',
            array( 'jquery' )
        );

        wp_enqueue_script(
            MASE_PREFIX.'app_js',
            MASE_URL.'static/js/app.js',
            array( 'jquery' )
        );

        wp_enqueue_script(
            MASE_PREFIX.'bs_multiselect_js',
            MASE_URL.'static/js/bs-multiselect/js/bootstrap-multiselect.js',
            array( 'jquery' )
        );

        wp_enqueue_script(
            MASE_PREFIX.'datatables_js',
            MASE_URL.'static/js/datatables/js/jquery.dataTables.min.js',
            array( 'jquery' )
        );

        wp_enqueue_script(
            MASE_PREFIX.'app_zone_manager_js',
            MASE_URL.'static/js/app_zone_manager.js',
            array( 'jquery' )
        );

        wp_enqueue_script(
            MASE_PREFIX.'app_statistics_js',
            MASE_URL.'static/js/app_statistics.js',
            array( 'jquery' )
        );

        wp_enqueue_media();

        wp_localize_script( MASE_PREFIX.'app_js', 'mase_app', array(
            't' => array('preview' => __('Preview', MASE_TEXT_DOMAIN)),
            'lng' => get_locale(),
            'mase_url' => MASE_URL,
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'admin_cc' => MASE::get_user_country(),
            'dow_select_all' => __('All Days of Week', MASE_TEXT_DOMAIN),
            'hod_select_all' => __('All Hours of Day', MASE_TEXT_DOMAIN),
            'selected' => __('selected', MASE_TEXT_DOMAIN),
            'remove_license_key' => __('Do you really want to remove your moreAds SE License Key?', MASE_TEXT_DOMAIN),
            'none' => __('none', MASE_TEXT_DOMAIN),
            'unknown' => __('unknown', MASE_TEXT_DOMAIN)

        ));

        wp_register_style( MASE_PREFIX.'bs', MASE_URL.'static/css/mase-bs.css', false, '1.0.1' );
        wp_enqueue_style( MASE_PREFIX.'bs' );

        wp_register_style( MASE_PREFIX.'s2', MASE_URL.'static/js/select2/css/select2.css', false, '1.0.1' );
        wp_enqueue_style( MASE_PREFIX.'s2' );

        wp_register_style( MASE_PREFIX.'masecss', MASE_URL.'static/css/mase.css', false, '1.0.1' );
        wp_enqueue_style( MASE_PREFIX.'masecss' );

        wp_register_style( MASE_PREFIX.'datatablescss', MASE_URL.'static/js/datatables/css/jquery.dataTables.min.css', false, '1.0.1' );
        wp_enqueue_style( MASE_PREFIX.'datatablescss' );

        wp_register_style( MASE_PREFIX.'bsmultiselectcss', MASE_URL.'static/js/bs-multiselect/css/bootstrap-multiselect.css', false, '1.0.1' );
        wp_enqueue_style( MASE_PREFIX.'bsmultiselectcss' );
    }

    public function handleAdCloneRequest() {
        if(isset($_GET['mase_clone']) && isset($_GET['mase_clone_id'])) {
            $new_post_id = MASE_Ads_Generic::handleAdClone($_GET['mase_clone_id']);
            $url = get_admin_url().'post.php?post='.$new_post_id.'&action=edit';
            header('Location: '.$url);
            die();
        }
    }
}